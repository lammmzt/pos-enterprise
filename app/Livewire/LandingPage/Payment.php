<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\BatchProduk;
use App\Models\MutasiStok; // <-- Tambahkan ini untuk model Mutasi Stok

use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class Payment extends Component
{
    public $pesananId;
    public $pesanan;
    public $metode_pembayaran = 'qris'; // Default
    
    // Properti Baru
    public $tipe_pesanan; 
    public $isKasir = false;

    // Properti khusus Delivery
    public $nomor_hp;
    public $alamat;
    public $catatan;

    public function mount($id)
    {
        $this->pesananId = $id;
        $this->pesanan = Pesanan::with('mangkuk.detailPesanan.produk')->findOrFail($id);

        // Keamanan: Pastikan pesanan ini milik user yang sedang login
        if ($this->pesanan->id_user !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak');
        }

        // Set Nilai Awal
        $this->tipe_pesanan = $this->pesanan->tipe_pesanan ?? 'takeaway';

        // Cek apakah user ini adalah 'antrean_' (Kasir)
        $namaUser = strtolower(Auth::user()->nama ?? Auth::user()->username ?? '');
        $this->isKasir = (strpos($namaUser, 'antrean_') !== false);

        // Inisialisasi form delivery dengan data user yang login
        $this->nomor_hp = Auth::user()->nomor_hp;
        $this->alamat = $this->pesanan->alamat ?? ''; 
        $this->catatan = $this->pesanan->catatan ?? '';
    }

    public function processPayment()
    {
        // 1. Siapkan data update
        $updateData = [
            'tipe_pesanan' => $this->tipe_pesanan,
            'metode_pembayaran' => $this->metode_pembayaran
        ];

        // 2. Jika tipe Delivery, validasi dan simpan alamat
        if ($this->tipe_pesanan === 'delivery') {
            $this->validate([
                'nomor_hp' => 'required|numeric',
                'alamat' => 'required|string|min:10',
            ]);
            
            $updateData['link_delivery'] = $this->alamat . ' | HP: ' . $this->nomor_hp;
            $updateData['catatan'] = $this->catatan;
        }

        $this->pesanan->update($updateData);

        // 3. Logika Pembayaran
        if ($this->metode_pembayaran === 'tunai') {
            $this->pesanan->update([
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'proses'
            ]);
            return redirect()->route('Order')->with('success', 'Pesanan Tunai Berhasil. Menunggu dimasak dapur.');
            
        } elseif ($this->metode_pembayaran === 'qris') {
            
            // --- LOGIKA CEK TOKEN LAMA (ANTI-REFRESH BUG) ---
            if ($this->pesanan->snap_token) {
                // Jika token sudah pernah dibuat, LANGSUNG panggil ulang token lama
                // Tanpa perlu memanggil API Midtrans lagi!
                $this->dispatch('pay-with-midtrans', token: $this->pesanan->snap_token);
                return; // Hentikan fungsi sampai di sini
            }

            // --- JIKA BELUM ADA TOKEN, BUAT BARU ---
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $this->pesanan->nomor_invoice,
                    'gross_amount' => $this->pesanan->total_harga,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->nama,
                    'phone' => Auth::user()->nomor_hp,
                ],
                'enabled_payments' => ['gopay', 'shopeepay', 'qris', 'bank_transfer'],
            ];

            try {
                // Dapatkan Token BARU dari Midtrans
                $snapToken = Snap::getSnapToken($params);
                
                // SIMPAN KE DATABASE AGAR BISA DIGUNAKAN LAGI
                $this->pesanan->update([
                    'snap_token' => $snapToken
                ]);

                // Trigger event Javascript untuk membuka Popup
                $this->dispatch('pay-with-midtrans', token: $snapToken);
                
            } catch (\Exception $e) {
                $this->dispatch('toast', type: 'error', message: 'Gagal memuat pembayaran: ' . $e->getMessage());
            }
        }
    }

     // Tambahkan fungsi ini di dalam class Payment Component Anda
    public function cancelExpiredOrder()
    {
        // Jika statusnya belum dibayar/proses, batalkan
        if ($this->pesanan->status_pembayaran !== 'lunas') {
            
            // 1. Kembalikan Stok (Logika Reverse FEFO)
            foreach ($this->pesanan->mangkuk as $mangkuk) {
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
                        'id_user' => $this->pesanan->id_user,
                        'tipe' => 'masuk',
                        'jumlah' => $detail->jumlah,
                        'tipe_referensi' => 'Penjualan',
                        'id_referensi' => $this->pesanan->id_pesanan,
                        'catatan' => 'Pengembalian stok (Otomatis) - Waktu pembayaran habis. Invoice: ' . $this->pesanan->nomor_invoice,
                    ]);

                    
                }
            }

            // 2. Ubah Status Pesanan
            $this->pesanan->update([
                'status_pembayaran' => 'gagal',
                'status_pesanan' => 'dibatalkan',
            ]);
            
            session()->flash('error', 'Waktu pembayaran telah habis. Pesanan otomatis dibatalkan.');
            return redirect()->route('Order');
        }

    }

    public function render()
    {
        $data['title'] = 'Payment';
        $data['active'] = 'Payment';
        return view('livewire.landing-page.payment', $data)->layout('components.layouts.guest', $data);
    }
}