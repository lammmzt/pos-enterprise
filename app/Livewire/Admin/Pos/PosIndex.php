<?php

namespace App\Livewire\Admin\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\MangkukPesanan;
use App\Models\DetailPesanan;
use App\Models\BatchProduk;
use App\Models\MutasiStok;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PosIndex extends Component
{
    use WithPagination;

    // Filter Produk
    public $search = '';
    public $filterKategori = '';

    // --- STATE 3-TIER (MANGKUK) ---
    public $bowls = [];
    public $activeBowlIndex = 0; // Index mangkuk yang sedang aktif dipilih Kasir

    // --- STATE PELANGGAN & CHECKOUT ---
    public $id_pelanggan = null; // Terhubung dengan Select2 nantinya
    public $tipe_pesanan = 'dinein'; // dinein, takeaway, delivery
    public $metode_pembayaran = 'Tunai'; // Tunai, QRIS (Kasir)
    public $uang_diterima = 0;
    public $catatan_global = '';
    
    // Info Pengiriman (Khusus Delivery)
    public $alamat_pengiriman = '';
    public $nomor_hp_pengiriman = '';

    // Modal & Kalkulasi
    public $isCheckoutModalOpen = false;
    public $total_harga = 0;
    public $kembalian = 0;

    public function mount()
    {
        // Inisialisasi Mangkuk Pertama saat halaman dimuat
        $this->addBowl('Pemesan 1');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterKategori() { $this->resetPage(); }

    public function render()
    {
        $query = Produk::where('status', 'aktif')->where('stok', '>', 0);

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
        }

        if ($this->filterKategori && $this->filterKategori !== 'all') {
            $query->where('id_kategori', $this->filterKategori);
        }

        $data['produks'] = $query->orderBy('nama', 'asc')->paginate(12);
        $data['kategoris'] = Kategori::all();
        $data['pelanggans'] = User::where('role', 'pelanggan')->where('status', 'aktif')->get();

        return view('livewire.admin.pos.pos-index', $data)->layout('components.layouts.app', ['title' => 'POS Kasir']);
    }

    // --- LOGIKA MANGKUK (BOWL) ---

    public function addBowl($nama = null)
    {
        $namaPemesan = $nama ?? 'Pemesan ' . (count($this->bowls) + 1);
        $this->bowls[] = [
            'id' => uniqid('bowl_'),
            'nama_pemesan' => $namaPemesan,
            'tipe_kuah' => 'Kuah Kencur (Original)',
            'level_pedas' => 1,
            'catatan' => '',
            'items' => [] // Berisi detail produk
        ];
        $this->activeBowlIndex = count($this->bowls) - 1;
    }

    public function setActiveBowl($index)
    {
        if (isset($this->bowls[$index])) {
            $this->activeBowlIndex = $index;
        }
    }

    public function removeBowl($index)
    {
        unset($this->bowls[$index]);
        $this->bowls = array_values($this->bowls); // Re-index array
        
        if (count($this->bowls) === 0) {
            $this->addBowl('Pemesan 1');
        } else {
            $this->activeBowlIndex = 0;
        }
        $this->calculateTotal();
    }

    // --- LOGIKA ITEM DALAM MANGKUK ---

    // Menghitung total qty produk X di seluruh mangkuk untuk validasi stok
    private function getGlobalQty($id_produk)
    {
        $total = 0;
        foreach ($this->bowls as $bowl) {
            if (isset($bowl['items'][$id_produk])) {
                $total += $bowl['items'][$id_produk]['qty'];
            }
        }
        return $total;
    }

    public function addToCart($id_produk)
    {
        $produk = Produk::find($id_produk);
        if (!$produk || $produk->stok <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Produk tidak ditemukan atau stok habis!');
            return;
        }

        // Cek apakah total qty di seluruh mangkuk sudah melebihi stok
        $currentGlobalQty = $this->getGlobalQty($id_produk);
        if ($currentGlobalQty >= $produk->stok) {
            $this->dispatch('toast', type: 'error', message: "Stok $produk->nama maksimal $produk->stok pcs!");
            return;
        }

        $activeBowl =& $this->bowls[$this->activeBowlIndex]; // Menggunakan Reference (&) untuk mengubah data langsung

        if (isset($activeBowl['items'][$id_produk])) {
            $activeBowl['items'][$id_produk]['qty']++;
        } else {
            $activeBowl['items'][$id_produk] = [
                'id' => $produk->id_produk,
                'nama' => $produk->nama,
                'harga' => $produk->harga_jual,
                'qty' => 1,
            ];
        }

        $this->calculateTotal();
    }

    public function updateQty($id_produk, $action)
    {
        $activeBowl =& $this->bowls[$this->activeBowlIndex];
        if (!isset($activeBowl['items'][$id_produk])) return;

        if ($action === 'increase') {
            $produk = Produk::find($id_produk);
            if ($this->getGlobalQty($id_produk) < $produk->stok) {
                $activeBowl['items'][$id_produk]['qty']++;
            } else {
                $this->dispatch('toast', type: 'error', message: 'Maksimal stok tercapai!');
            }
        } elseif ($action === 'decrease') {
            if ($activeBowl['items'][$id_produk]['qty'] > 1) {
                $activeBowl['items'][$id_produk]['qty']--;
            } else {
                unset($activeBowl['items'][$id_produk]);
            }
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_harga = 0;
        foreach ($this->bowls as $bowl) {
            foreach ($bowl['items'] as $item) {
                $this->total_harga += $item['harga'] * $item['qty'];
            }
        }
        $this->updatedUangDiterima();
    }

    public function updatedUangDiterima()
    {
        $uang = (float) str_replace('.', '', $this->uang_diterima);
        $this->kembalian = $uang - $this->total_harga;
    }

    public function updatedIdPelanggan($value)
    {
        // Jika pelanggan dipilih dan tipe delivery, auto-fill alamat & no HP
        if ($value && $this->tipe_pesanan === 'delivery') {
            $pelanggan = User::find($value);
            if ($pelanggan) {
                $this->alamat_pengiriman = $pelanggan->alamat;
                $this->nomor_hp_pengiriman = $pelanggan->nomor_hp;
                // Asumsi ada field nomor_hp di tabel users nantinya, untuk sekarang dikosongkan/mockup
            }
        }
    }

    public function openCheckoutModal()
    {
        $this->calculateTotal();
        if ($this->total_harga == 0) {
            $this->dispatch('toast', type: 'error', message: 'Pilih minimal 1 menu untuk dipesan!');
            return;
        }
        $this->isCheckoutModalOpen = true;
    }

    // --- ALGORITMA UTAMA FEFO (3-TIER) ---

    public function prosesCheckout()
    {
        if (empty($this->id_pelanggan)) {
            $this->dispatch('toast', type: 'error', message: 'Data Pelanggan wajib dipilih sebelum checkout!');
            return;
        }
        
        $uang_diterima_float = (float) str_replace('.', '', $this->uang_diterima);
        
        if ($this->metode_pembayaran === 'Tunai' && $uang_diterima_float < $this->total_harga) {
            $this->dispatch('toast', type: 'error', message: 'Uang diterima kurang dari total belanja!');
            return;
        }

        if ($this->tipe_pesanan === 'delivery' && (empty($this->alamat_pengiriman) || empty($this->id_pelanggan))) {
            $this->dispatch('toast', type: 'error', message: 'Pelanggan dan Alamat wajib diisi untuk Delivery!');
            return;
        }

        try {
            DB::transaction(function () {
                // 1. Buat Master Pesanan
                $invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
                
                $pesanan = Pesanan::create([
                    'id_user' => $this->id_pelanggan, // Bisa null jika tamu
                    'id_kasir' => Auth::id(),
                    'nomor_invoice' => $invoice_no,
                    'total_harga' => $this->total_harga,
                    'status_pembayaran' => 'lunas',
                    'status_pesanan' => 'proses', // Sesuai PRD, pesanan butuh dimasak
                    'tipe_pesanan' => $this->tipe_pesanan,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'link_delivery' => $this->tipe_pesanan === 'delivery' ? $this->alamat_pengiriman : null,
                    'catatan' => $this->catatan_global,
                ]);

                // 2. Loop setiap Mangkuk
                foreach ($this->bowls as $bowl) {
                    // Cek jika mangkuk ini ada isinya
                    if (count($bowl['items']) === 0) continue;

                    $mangkuk = MangkukPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'nama_pemesan' => $bowl['nama_pemesan'],
                        'tipe_kuah' => $bowl['tipe_kuah'],
                        'level_pedas' => $bowl['level_pedas'],
                        'catatan' => $bowl['catatan'],
                    ]);

                    // 3. Loop Item per Mangkuk & Jalankan FEFO
                    foreach ($bowl['items'] as $item) {
                        $qtyNeeded = $item['qty'];
                        $produkId = $item['id'];

                        // Algoritma FEFO (First Expired First Out)
                        $batches = BatchProduk::where('id_produk', $produkId)
                            ->where('jumlah', '>', 0)
                            ->orderByRaw('ISNULL(tanggal_kedaluwarsa), tanggal_kedaluwarsa ASC')
                            ->lockForUpdate()
                            ->get();

                        $sisaKebutuhan = $qtyNeeded;

                        foreach ($batches as $batch) {
                            if ($sisaKebutuhan <= 0) break;

                            $qtyDiambil = min($batch->jumlah, $sisaKebutuhan);
                            $batch->decrement('jumlah', $qtyDiambil);
                            Produk::where('id_produk', $produkId)->decrement('stok', $qtyDiambil);

                            // Simpan Detail Pesanan yang terhubung ke Mangkuk, bukan ke Pesanan langsung
                            DetailPesanan::create([
                                'id_mangkuk' => $mangkuk->id_mangkuk,
                                'id_produk' => $produkId,
                                'id_batch' => $batch->id_batch,
                                'jumlah' => $qtyDiambil,
                                'harga' => $item['harga'],
                                'subtotal' => $qtyDiambil * $item['harga'],
                            ]);

                            // Mutasi Stok
                            MutasiStok::create([
                                'id_produk' => $produkId,
                                'id_batch' => $batch->id_batch,
                                'id_user' => Auth::id(),
                                'tipe' => 'keluar',
                                'jumlah' => $qtyDiambil,
                                'tipe_referensi' => 'Penjualan',
                                'id_referensi' => $pesanan->id_pesanan,
                                'catatan' => 'POS Mangkuk ' . $bowl['nama_pemesan'] . ' - INV: ' . $invoice_no,
                            ]);

                            $sisaKebutuhan -= $qtyDiambil;
                        }

                        if ($sisaKebutuhan > 0) {
                            throw ValidationException::withMessages([
                                'error' => "Sistem mendeteksi selisih stok fisik pada produk: {$item['nama']}."
                            ]);
                        }
                    }
                }

                // Reset Keranjang
                $this->bowls = [];
                $this->addBowl('Pemesan 1');
                $this->id_pelanggan = null;
                $this->isCheckoutModalOpen = false;
                $this->calculateTotal();
                
                // Trigger event ke browser untuk mencetak struk Thermal 58mm (dengan parameter layout mangkuk)
                $this->dispatch('cetak-struk', id_pesanan: $pesanan->id_pesanan);
                $this->dispatch('toast', type: 'success', message: 'Transaksi Berhasil & Masuk Antrean Dapur!');
                
            });
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}