<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class MidtransWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // Terima data dari Midtrans
        $order_id = $request->order_id;
        $status_code = $request->status_code;
        $gross_amount = $request->gross_amount;
        $transaction_status = $request->transaction_status;
        $signature_key = $request->signature_key;

        // 1. Verifikasi Keamanan (Signature Key)
        $my_signature = hash('sha512', $order_id . $status_code . $gross_amount . $serverKey);
        
        if ($signature_key !== $my_signature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Cari Pesanan berdasarkan nomor invoice
        $pesanan = Pesanan::where('nomor_invoice', $order_id)->first();
        if (!$pesanan) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Update Status Berdasarkan Respon Midtrans
        if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
            // PEMBAYARAN BERHASIL
            $pesanan->update([
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'proses' // Otomatis masuk dapur
            ]);
        } 
        elseif ($transaction_status == 'expire' || $transaction_status == 'cancel' || $transaction_status == 'deny') {
            // PEMBAYARAN GAGAL / KEDALUWARSA
            if ($pesanan->status_pembayaran !== 'lunas') {
                $pesanan->update([
                    'status_pembayaran' => 'gagal',
                    'status_pesanan' => 'dibatalkan'
                ]);
                
                // CATATAN: Panggil fungsi pengembalian stok Anda di sini jika diperlukan
            }
        }

        return response()->json(['message' => 'Success']);
    }
}