<?php

namespace App\Livewire\Admin\Pos;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\BatchProduk;
use App\Models\MutasiStok; // <-- Tambahkan ini untuk model Mutasi Stok

class PesananAktifIndex extends Component
{
    public $search = '';

    // State untuk Modal
    public $showModalPembayaran = false;
    public $showModalBatalPesanan = false;
    public $showModalSelesai = false;
    public $showModalDelivery = false;

    // State untuk Data yang dipilih
    public $selectedPesananId = null;
    public $inputLinkDelivery = '';
    
    // State tambahan untuk info di Modal Pembayaran
    public $paymentTotal = 0;
    public $paymentMethod = '';
    public $paymentCustomer = '';
    public $catatanPembatalan = '';

    public function render()
    {
        $query = Pesanan::with(['mangkuk.detailPesanan.produk', 'pelanggan', 'kasir'])
            ->where('status_pembayaran', 'proses_bayar')
            ->orWhere('status_pesanan', 'menunggu_pembayaran')
            ->orWhere('status_pesanan', 'proses')
            ->orWhere('status_pesanan', 'delivery');

        if ($this->search) {
            $query->where('nomor_invoice', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
        }

        $pesanans = $query->orderBy('created_at', 'asc')->get();
        // dd($pesanans);
        return view('livewire.admin.pos.pesanan-aktif-index', [
            'pesanans' => $pesanans
        ])->layout('components.layouts.app', ['title' => 'Dapur: Pesanan Aktif']);
    }

    // --- FITUR PEMBAYARAN ---
    public function openModalPembayaran($id)
    {
        $this->selectedPesananId = $id;
        $pesanan = Pesanan::with('pelanggan')->find($id);
        
        if ($pesanan) {
            $this->paymentTotal = $pesanan->total_harga;
            $this->paymentMethod = $pesanan->metode_pembayaran ?? 'Belum ditentukan';
            $this->paymentCustomer = $pesanan->pelanggan->nama ?? 'Pelanggan Umum (Walk-in)';
            $this->showModalPembayaran = true;
        }
    }

    public function konfirmasiPembayaran()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        if ($pesanan) {
            $pesanan->update(['status_pembayaran' => 'lunas', 'status_pesanan' => 'proses', 'id_kasir' => auth()->id()]);
            $this->dispatch('toast', type: 'success', message: 'Pembayaran berhasil dikonfirmasi!');
            
            // Trigger buka tab cetak struk otomatis
            $this->dispatch('cetak-struk', id_pesanan: $pesanan->id_pesanan);
        }
        $this->resetModal();
    }

    // fitur batal pesanan
    public function openModalBatal($id)
    {
        $this->selectedPesananId = $id;
        $pesanan = Pesanan::with('pelanggan')->find($id);
        
        if ($pesanan) {
            $this->paymentTotal = $pesanan->total_harga;
            $this->paymentMethod = $pesanan->metode_pembayaran ?? 'Belum ditentukan';
            $this->paymentCustomer = $pesanan->pelanggan->nama ?? 'Pelanggan Umum (Walk-in)';
            $this->showModalBatalPesanan = true;
        }
    }

    public function batalkanPesanan()
    {
        // reference on top
        // Jika statusnya belum dibayar/proses, batalkan
        $pesanan = Pesanan::find($this->selectedPesananId);
        if ($pesanan->status_pembayaran !== 'lunas') {
            $this->validate([
                'catatanPembatalan' => 'required|string|min:5|max:255',
            ], [
                'catatanPembatalan.required' => 'Alasan pembatalan wajib diisi.',
                'catatanPembatalan.min' => 'Alasan terlalu singkat.'
            ]);
            // 1. Kembalikan Stok (Logika Reverse FEFO)
            foreach ($pesanan->mangkuk as $mangkuk) {
                foreach ($mangkuk->detailPesanan as $detail) {
                    
                    // A. Kembalikan ke Master Produk
                    Produk::where('id_produk', $detail->id_produk)
                        ->increment('stok', $detail->jumlah);
                    
                    // B. Kembalikan ke Batch (FEFO)
                    if ($detail->id_batch) {
                        BatchProduk::where('id_batch', $detail->id_batch)
                            ->increment('jumlah', $detail->jumlah);
                    }

                    // C. Catat ke Tabel Mutasi Stok (Menambah Stok)
                    MutasiStok::create([
                        'id_produk' => $detail->id_produk,
                        'id_batch' => $detail->id_batch,
                        'id_user' => auth()->id(),
                        'tipe' => 'masuk',
                        'jumlah' => $detail->jumlah,
                        'tipe_referensi' => 'Penjualan',
                        'id_referensi' => $pesanan->id_pesanan,
                        'catatan' => 'Pembatalan Pesanan Invoice: ' . $pesanan->nomor_invoice. ' dengan catatan : ' . $this->catatanPembatalan,
                    ]);

                }
            }

            // 2. Ubah Status Pesanan
            $pesanan->update([
                'status_pembayaran' => 'gagal',
                'status_pesanan' => 'dibatalkan',
            ]);
            $this->dispatch('toast', type: 'success', message: 'Pesanan berhasil dibatalkan!');
        }
        $this->resetModal();
    }


    // --- FITUR UPDATE LINK DELIVERY ---
    public function openModalDelivery($id, $currentLink)
    {
        $this->selectedPesananId = $id;
        $this->inputLinkDelivery = $currentLink;
        $this->showModalDelivery = true;
    }

    public function simpanLinkDelivery()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        if ($pesanan) {
            // Update link dan otomatis selesaikan pesanan (karena sudah diserahkan ke kurir)
            $pesanan->update([
                'link_delivery' => $this->inputLinkDelivery,
                'status_pesanan' => 'selesai' 
            ]);
            $this->dispatch('toast', type: 'success', message: 'Link pengiriman disimpan & Pesanan selesai!');
        }
        $this->resetModal();
    }

    // --- FITUR SELESAI PESANAN (DINE-IN / TAKEAWAY) ---
    public function openModalSelesai($id)
    {
        $this->selectedPesananId = $id;
        $this->showModalSelesai = true;
    }

    public function selesaikanPesanan()
    {
        $pesanan = Pesanan::find($this->selectedPesananId);
        
        if ($pesanan) {
            if ($pesanan->status_pembayaran === 'belum_bayar' || $pesanan->status_pembayaran === 'prose_bayar') {
                $this->dispatch('toast', type: 'error', message: 'Gagal! Pastikan pelanggan sudah membayar.');
                $this->resetModal();
                return;
            }

            $pesanan->update(['status_pesanan' => 'selesai','id_kasir' => auth()->id()]);
            $this->dispatch('toast', type: 'success', message: 'Pesanan ' . $pesanan->nomor_invoice . ' diselesaikan!');
        }
        
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->showModalPembayaran = false;
        $this->showModalBatalPesanan = false;
        $this->showModalSelesai = false;
        $this->showModalDelivery = false;
        $this->catatanPembatalan = '';
        $this->selectedPesananId = null;
        $this->inputLinkDelivery = '';
        $this->dispatch('focus-search-input');
    }
}