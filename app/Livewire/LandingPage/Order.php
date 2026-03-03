<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\MangkukPesanan;
use App\Models\DetailPesanan;
use App\Models\BatchProduk;
use App\Models\Pengaturan;
use App\Models\MutasiStok; // <-- Tambahkan model MutasiStok
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Order extends Component
{
    // Filter
    public $search = '';
    public $activeCategory = 'all';

    // State 3-Tier (Mangkuk)
    public $bowls = [];
    public $activeBowlIndex = 0;
    
    // Kalkulasi
    public $total_harga = 0;

    public function mount()
    {
       // Cek apakah ada data reorder dari halaman Riwayat
        if (session()->has('reorder_cart')) {
            // Ambil data dari session dan masukkan ke keranjang aktif
            $this->bowls = session()->get('reorder_cart');
            $this->activeBowlIndex = 0;
            
            // Hitung total harga sesuai mangkuk yang baru di-load
            $this->calculateTotal();
            
            // Hapus session agar tidak terus-terusan meload jika halaman di-refresh
            session()->forget('reorder_cart');
            
            // (Opsional) Kirim notifikasi sukses
            session()->flash('success', 'Keranjang berhasil diisi dengan pesanan lama Anda.');
        } else {
            // Jika tidak ada reorder, inisialisasi Mangkuk Pertama kosong seperti biasa
            $this->addBowl('Mangkuk 1');
        }
    }

    public function render()
    {
        // Ambil Data Produk (Pastikan stok > 0)
        $query = Produk::where('status', 'aktif')->where('stok', '>', 0);
        
        $data['data_toko'] = [
            'status_aktif_toko' => Pengaturan::where('kunci', 'status_aktif_toko')->first(),
            'jam_buka_toko' => Pengaturan::where('kunci', 'jam_buka_toko')->first(),
            'hari_buka_toko' => Pengaturan::where('kunci', 'hari_buka_toko')->first(),
        ];
        
        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }
        if ($this->activeCategory !== 'all') {
            $query->where('id_kategori', $this->activeCategory);
        }

        $data['produks'] = $query->get();
        $data['kategoris'] = Kategori::all();
        $data['title'] = 'Menu Custom Bowl';
        $data['active'] = 'Order';

        return view('livewire.landing-page.order', $data)->layout('components.layouts.guest', $data);
    }

    public function setCategory($id_kategori)
    {
        $this->activeCategory = $id_kategori;
    }

    // --- LOGIKA MANGKUK ---
    public function addBowl($nama = null)
    {
        $namaPemesan = $nama ?? 'Mangkuk ' . (count($this->bowls) + 1);
        $this->bowls[] = [
            'id' => uniqid('bowl_'),
            'nama_pemesan' => $namaPemesan,
            'tipe_kuah' => 'Kuah Kencur (Original)',
            'level_pedas' => 1,
            'catatan' => '',
            'items' => [] 
        ];
        $this->activeBowlIndex = count($this->bowls) - 1;
    }

    public function setActiveBowl($index)
    {
        if (isset($this->bowls[$index])) $this->activeBowlIndex = $index;
    }

    public function removeBowl($index)
    {
        unset($this->bowls[$index]);
        $this->bowls = array_values($this->bowls);
        if (count($this->bowls) === 0) $this->addBowl('Mangkuk 1');
        else $this->activeBowlIndex = 0;
        $this->calculateTotal();
    }

    // --- LOGIKA ITEM & STOK ---
    private function getGlobalQty($id_produk)
    {
        $total = 0;
        foreach ($this->bowls as $bowl) {
            if (isset($bowl['items'][$id_produk])) $total += $bowl['items'][$id_produk]['qty'];
        }
        return $total;
    }

    public function updateItem($id_produk, $change)
    {
        $produk = Produk::find($id_produk);
        if (!$produk) return;

        $activeBowl =& $this->bowls[$this->activeBowlIndex];
        $currentQty = $activeBowl['items'][$id_produk]['qty'] ?? 0;

        if ($change > 0) {
            // Cek Stok Maksimal
            if ($this->getGlobalQty($id_produk) >= $produk->stok) {
                $this->dispatch('toast', type: 'error', message: "Stok {$produk->nama} habis!");
                return;
            }
            
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
        } elseif ($change < 0) {
            if ($currentQty > 1) {
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
    }

    // --- CHECKOUT & BUAT INVOICE ---
    public function checkout()
    {
        $this->calculateTotal();
        if ($this->total_harga == 0) {
            $this->dispatch('toast', type: 'error', message: 'Keranjang masih kosong!');
            return;
        }

        // Pastikan User Login
        if (!Auth::check()) {
            return redirect()->route('Auth')->with('error', 'Silakan login terlebih dahulu untuk memesan.');
        }

        // check apakah ada status pesanan yang belum bayar
        $pesanan = Pesanan::where('id_user', Auth::id())
            ->where('status_pembayaran', 'belum_bayar')
            ->where('status_pesanan', '!=', 'dibatalkan')
            ->first();

        if ($pesanan) {
            $this->dispatch('toast', type: 'error', message: 'Gagal! Pastikan pelanggan sudah membayar pesanan sebelumnya.');
            return;
        }
        
        $pengaturan = Pengaturan::where('kunci', 'status_aktif_toko')->first();

        if (!$pengaturan || $pengaturan->nilai !== 'aktif') {
            $this->dispatch('toast', type: 'error', message: 'Toko sedang tutup. Coba lihat pada jadwal diatas.');
            return;
        }

        // Pastikan Stok Mencukupi
        foreach ($this->bowls as $bowl) {
            foreach ($bowl['items'] as $item) {
                $produk = Produk::find($item['id']);
                if ($produk->stok < $item['qty']) {
                    $this->dispatch('toast', type: 'error', message: "Stok {$produk->nama} tidak mencukupi!");
                    return;
                }
            }
        }

        $pesananId = null;

        try {
            DB::transaction(function () use (&$pesananId) {
                $invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
                
                // 1. Buat Data Pesanan (Status: belum_bayar)
                $pesanan = Pesanan::create([
                    'id_user' => Auth::id(),
                    'id_kasir' => null, // Karena pesan mandiri online
                    'nomor_invoice' => $invoice_no,
                    'total_harga' => $this->total_harga,
                    'status_pembayaran' => 'belum_bayar',
                    'status_pesanan' => 'menunggu_pembayaran', // Masuk antrean dapur tapi ditahan status pembayarannya
                ]);
                
                $pesananId = $pesanan->id_pesanan;

                // 2. Simpan Mangkuk dan FEFO (Pemotongan Stok)
                foreach ($this->bowls as $bowl) {
                    if (count($bowl['items']) === 0) continue;

                    $mangkuk = MangkukPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'nama_pemesan' => $bowl['nama_pemesan'],
                        'tipe_kuah' => $bowl['tipe_kuah'],
                        'level_pedas' => $bowl['level_pedas'],
                        'catatan' => $bowl['catatan'],
                    ]);

                    foreach ($bowl['items'] as $item) {
                        $qtyNeeded = $item['qty'];
                        $batches = BatchProduk::where('id_produk', $item['id'])
                            ->where('jumlah', '>', 0)
                            ->orderByRaw('ISNULL(tanggal_kedaluwarsa), tanggal_kedaluwarsa ASC')
                            ->lockForUpdate()
                            ->get();

                        foreach ($batches as $batch) {
                            if ($qtyNeeded <= 0) break;
                            
                            $qtyDiambil = min($batch->jumlah, $qtyNeeded);
                            
                            // A. Potong Stok dari Batch
                            $batch->decrement('jumlah', $qtyDiambil);
                            
                            // B. Potong Stok dari Produk Induk
                            Produk::where('id_produk', $item['id'])->decrement('stok', $qtyDiambil);

                            // C. Catat Detail Pesanan
                            DetailPesanan::create([
                                'id_mangkuk' => $mangkuk->id_mangkuk,
                                'id_produk' => $item['id'],
                                'id_batch' => $batch->id_batch,
                                'jumlah' => $qtyDiambil,
                                'harga' => $item['harga'],
                                'subtotal' => $qtyDiambil * $item['harga'],
                            ]);
                            MutasiStok::create([
                                'id_produk' => $item['id'],
                                'id_batch' => $batch->id_batch,
                                'id_user' => Auth::id(),
                                'tipe' => 'keluar',
                                'jumlah' => $qtyDiambil,
                                'tipe_referensi' => 'Penjualan',
                                'id_referensi' => $pesanan->id_pesanan,
                                'catatan' => 'Penjualan Mangkuk: ' . $mangkuk->nama_pemesan . ' (Invoice: ' . $pesanan->nomor_invoice . ')',
                                
                            ]);

                            $qtyNeeded -= $qtyDiambil;
                        }
                    }
                }
            });

            // Redirect ke halaman Pembayaran
            $this->redirect(route('Payment', ['id' => $pesananId]));

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}