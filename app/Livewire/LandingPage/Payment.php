<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class Payment extends Component
{
    public $pesananId;
    public $pesanan;
    public $metode_pembayaran = 'qris'; // Default

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

        // Jika tipe delivery, isi form dengan data user
        if ($this->pesanan->tipe_pesanan === 'delivery') {
            $this->nomor_hp = Auth::user()->nomor_hp;
            $this->alamat = $this->pesanan->alamat ?? ''; 
            $this->catatan = $this->pesanan->catatan ?? '';
        }
    }

    // Mengecek apakah user ini adalah 'antrean_'
    public function getIsKasirAttribute()
    {
        $namaUser = Auth::user()->nama ?? Auth::user()->username ?? '';
        return strpos(strtolower($namaUser), 'antrean_') !== false;
    }

    public function processPayment()
    {
        // 1. Simpan perubahan alamat jika tipe Delivery
        if ($this->pesanan->tipe_pesanan === 'delivery') {
            $this->validate([
                'nomor_hp' => 'required|numeric',
                'alamat' => 'required|string|min:10',
            ]);
            
            // Simpan ke pesanan
            $this->pesanan->update([
                'link_delivery' => $this->alamat . ' | HP: ' . $this->nomor_hp, // Asumsi ini kolom alamat di DB Anda
                'catatan' => $this->catatan
            ]);
        }

        $this->pesanan->update(['metode_pembayaran' => $this->metode_pembayaran]);

        // 2. Logika Pembayaran
        if ($this->metode_pembayaran === 'tunai') {
            // Jika Tunai (Hanya Kasir), langsung selesai dan masuk dapur
            $this->pesanan->update([
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'proses'
            ]);
            
            // Redirect ke halaman sukses
            return redirect()->route('Order')->with('success', 'Pesanan Tunai Berhasil. Silakan ke kasir.');
            
        } elseif ($this->metode_pembayaran === 'qris') {
            // Konfigurasi Midtrans
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
                // Dapatkan Token dari Midtrans
                $snapToken = Snap::getSnapToken($params);
                
                // Trigger event Javascript untuk membuka Popup Midtrans
                $this->dispatch('pay-with-midtrans', token: $snapToken);
            } catch (\Exception $e) {
                $this->dispatch('toast', type: 'error', message: 'Gagal memuat pembayaran: ' . $e->getMessage());
            }
        }
    }

    public function render()
    {
        $data['title'] = 'Payment';
        $data['active'] = 'Payment';
        return view('livewire.landing-page.payment', $data)->layout('components.layouts.guest', $data);
    }
}