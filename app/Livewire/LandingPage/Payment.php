<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
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
    public $tipe_pembayaran;
    public $status_pembayaran;
    // public $status_pesanan; 
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
            return redirect()->route('Riwayat');
        }

        // Keamanan: Pastikan pesanan belum dibayar
        if ($this->pesanan->status_pembayaran !== 'belum_bayar' && $this->pesanan->status_pembayaran !== 'proses_bayar') {
            return redirect()->route('Riwayat');
        }

        // Set Nilai Awal
        $this->tipe_pesanan = $this->pesanan->tipe_pesanan ?? 'takeaway';
        $this->metode_pembayaran = $this->pesanan->metode_pembayaran ?? 'qris';
        $this->status_pembayaran = $this->pesanan->status_pembayaran ?? 'belum_bayar';
        // $this->status_pesanan = $this->pesanan->status_pesanan ?? 'menunggu_pembayaran';

        // Cek apakah user ini adalah 'antrean_' (Kasir)
        $usernameUser = strtolower(Auth::user()->username ?? Auth::user()->username ?? '');
        $this->isKasir = (strpos($usernameUser, 'antrean_') !== false);

        // Inisialisasi form delivery dengan data user yang login
        $this->nomor_hp = Auth::user()->no_hp;
        $this->alamat = Auth::user()->alamat ?? ''; 
        $this->catatan = Auth::user()->catatan ?? '';
    }

    
    public function render()
    {
        $data['title'] = 'Payment';
        $data['active'] = 'Payment';
        return view('livewire.landing-page.payment', $data)->layout('components.layouts.guest', $data);
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
            
            $updateData['catatan'] = $this->catatan;
        }

        $this->pesanan->update($updateData);

        // 3. Logika Pembayaran
        if ($this->metode_pembayaran === 'tunai') {
            $this->pesanan->update([
                'status_pembayaran' => 'proses_bayar',
                'tipe_pesanan' => $this->tipe_pesanan
            ]);
            // open modal qrcode 
            // reload data and open modal
            $this->dispatch('open-qrcode-modal');
            return;
            // return redirect()->route('Order')->with('success', 'Pesanan Tunai Berhasil. Menunggu dimasak dapur.');
            
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
                    'snap_token' => $snapToken,
                    'status_pembayaran' => 'proses_bayar',
                    'tipe_pesanan' => $this->tipe_pesanan
                ]);

                // jika tipe delivery, simpan alamat, no wa dan catatan kedalam data user
                if ($this->tipe_pesanan === 'delivery') {
                    User::where('id_user', Auth::user()->id_user)->update([
                        'alamat' => $this->alamat,
                        'no_hp' => $this->nomor_hp,
                        'catatan' => $this->catatan,
                    ]);
                }
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

    // Tambahkan fungsi ini di dalam class Payment Component Anda
    public function paymentSuccessCallback()
    {
        // Fungsi ini dipanggil dari JS saat popup sukses.
        // Kita paksa update status di UI (meskipun Webhook juga bekerja di background)
        $this->pesanan->refresh();
        if ($this->pesanan->status_pembayaran === 'proses_bayar') {
            $this->pesanan->update([
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'proses'
            ]);
        }

        session()->flash('success', 'Pembayaran berhasil! Pesanan Anda sedang dimasak.');
        return redirect()->route('Riwayat'); // Lebih baik diarahkan ke Riwayat Pesanan
    }

    // #[\Livewire\Attributes\On('check-payment-status')]
    #[On('check-payment-status')]
    public function checkPaymentStatus()
    {
        // 1. Cek apakah batas waktu 20 menit sudah lewat secara sistem (Waktu Server)
        $waktuDibuat = \Carbon\Carbon::parse($this->pesanan->created_at);
        $waktuKedaluwarsa = $waktuDibuat->copy()->addMinutes(20);
        $waktuSudahHabis = now()->greaterThanOrEqualTo($waktuKedaluwarsa);

        // 2. Jika metode QRIS dan sedang proses bayar, hubungi Midtrans
        if ($this->pesanan->metode_pembayaran === 'qris' && $this->pesanan->status_pembayaran === 'proses_bayar') {
            
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            try {
                // Tanya status asli ke Midtrans berdasarkan Nomor Invoice
                $status = \Midtrans\Transaction::status($this->pesanan->nomor_invoice);

                // A. Jika di Midtrans ternyata SUKSES (settlement/capture)
                if ($status->transaction_status == 'capture' || $status->transaction_status == 'settlement') {
                    
                    $this->pesanan->update([
                        'status_pembayaran' => 'lunas',
                        'status_pesanan' => 'proses' 
                    ]);

                    session()->flash('success', 'Pembayaran terkonfirmasi! Pesanan Anda sedang diproses.');
                    return redirect()->route('Order'); 
                } 
                // B. Jika dari Midtrans sudah divalidasi KEDALUWARSA / BATAL
                elseif ($status->transaction_status == 'expire' || $status->transaction_status == 'cancel') {
                    $this->cancelExpiredOrder(); 
                    return; // Hentikan eksekusi
                }
                
                // C. Jika statusnya 'pending', TIDAK ADA ELSE DI SINI. 
                // Biarkan saja, jangan di-cancel, agar pelanggan bisa lanjut bayar.

            } catch (\Exception $e) {
                // Abaikan jika order tidak ditemukan di Midtrans (belum terbuat)
            }
        }

        // 3. Fallback/Keamanan Ekstra: 
        // Jika pelanggan menutup modal, dan ternyata umurnya SUDAH lebih dari 20 menit
        // DAN belum lunas, maka pastikan dibatalkan.
        if ($waktuSudahHabis && $this->pesanan->status_pembayaran !== 'lunas') {
            $this->cancelExpiredOrder();
        }
    }
}