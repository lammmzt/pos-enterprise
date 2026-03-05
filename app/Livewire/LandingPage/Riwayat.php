<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\Testimoni; // Sesuaikan dengan model Ulasan/Review Anda
use Illuminate\Support\Facades\Auth;

class Riwayat extends Component
{
    public $activeTab = 'all'; // 'all', 'active', 'completed'
    
    // Properti Modal Detail
    public $selectedPesanan = null;

    // Properti Modal Review
    public $reviewPesananId = null;
    public $rating = 0;
    public $ulasan = '';

    public function render()
    {
        // Ambil data pesanan milik user yang sedang login
        $query = Pesanan::with(['mangkuk.detailPesanan.produk', 'testimoni']) // Asumsi relasi testimoni ada
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc');

        // Logika Filter Tab
        if ($this->activeTab === 'active') {
            // Status yang masih berjalan
            $query->whereNotIn('status_pesanan', ['selesai', 'dibatalkan']);
        } elseif ($this->activeTab === 'completed') {
            // Status yang sudah tamat
            $query->whereIn('status_pesanan', ['selesai', 'dibatalkan']);
        }

        $data['pesanans'] = $query->get();
        $data['title'] = 'Riwayat Pesanan';
        $data['active'] = 'Riwayat';

        return view('livewire.landing-page.riwayat', $data)->layout('components.layouts.guest', $data);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Membuka Detail Pesanan
    public function openDetail($id)
    {
        $this->selectedPesanan = Pesanan::with('mangkuk.detailPesanan.produk')->find($id);
        $this->dispatch('open-detail-modal'); // Trigger Alpine JS
    }

    // Membuka Modal Review
    public function openReview($id)
    {
        $this->reviewPesananId = $id;
        $this->rating = 0;
        $this->ulasan = '';
        $this->dispatch('open-review-modal'); // Trigger Alpine JS
    }

    // Set Bintang
    public function setRating($val)
    {
        $this->rating = $val;
    }

    // Submit Review ke DB
    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|min:1|max:5',
            'ulasan' => 'nullable|string|max:500'
        ]);

        // Simpan ke Database (Sesuaikan dengan tabel yang Anda miliki)
        Testimoni::create([
            'id_user' => Auth::id(),
            'id_pesanan' => $this->reviewPesananId,
            'rating' => $this->rating,
            'ulasan' => $this->ulasan,
            'status_tampil' => $this->rating > 3 ? true : false
        ]);

        $this->dispatch('close-review-modal');
        $this->dispatch('toast', type: 'success', message: 'Terima kasih atas ulasan Anda!');
    }

    // Fungsi untuk Pesan Lagi (Reorder)
    public function reOrder($id_pesanan)
    {
        // 1. Ambil data pesanan lama beserta relasinya
        $oldPesanan = Pesanan::with('mangkuk.detailPesanan.produk')->find($id_pesanan);

        if (!$oldPesanan) {
            $this->dispatch('toast', type: 'error', message: 'Pesanan tidak ditemukan.');
            return;
        }

        $newBowls = [];

        // 2. Rancang ulang format mangkuk agar sesuai dengan format keranjang di Order.php
        foreach ($oldPesanan->mangkuk as $oldMangkuk) {
            $items = [];
            
            foreach ($oldMangkuk->detailPesanan as $detail) {
                // Pengecekan cerdas: Pastikan produk masih aktif dan stoknya mencukupi
                if ($detail->produk && $detail->produk->status === 'aktif' && $detail->produk->stok > 0) {
                    
                    // Batasi jumlah jika stok saat ini lebih sedikit dari pesanan lama
                    $qtyToAdd = min($detail->jumlah, $detail->produk->stok);

                    $items[$detail->id_produk] = [
                        'id' => $detail->id_produk,
                        'nama' => $detail->produk->nama,
                        'harga' => $detail->produk->harga_jual, // Gunakan harga terbaru dari database, bukan harga lama
                        'qty' => $qtyToAdd,
                    ];
                }
            }
            
            // 3. Masukkan mangkuk hanya jika masih ada item yang tersedia di dalamnya
            if (count($items) > 0) {
                $newBowls[] = [
                    'id' => uniqid('bowl_'),
                    'nama_pemesan' => $oldMangkuk->nama_pemesan,
                    'tipe_kuah' => $oldMangkuk->tipe_kuah,
                    'level_pedas' => $oldMangkuk->level_pedas,
                    'catatan' => $oldMangkuk->catatan,
                    'items' => $items
                ];
            }
        }
        
        // 4. Jika ada data yang berhasil disalin, lempar ke Session dan arahkan ke halaman Order
        if (count($newBowls) > 0) {
            session()->put('reorder_cart', $newBowls);
            return redirect()->route('Order'); // Arahkan ke rute Order
        } else {
            $this->dispatch('toast', type: 'error', message: 'Maaf, semua menu dari pesanan lama ini sedang kosong atau tidak aktif.');
        }
    }
}