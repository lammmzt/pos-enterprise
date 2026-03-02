<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $pesanan = Pesanan::where('nomor_invoice', $request->order_id)->first();
            
            if ($pesanan) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    // Pembayaran Berhasil
                    $pesanan->update([
                        'status_pembayaran' => 'lunas',
                        // Jika ingin otomatis masuk antrean dapur, pastikan status_pesanan = 'proses'
                        'status_pesanan' => 'proses' 
                    ]);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                    // Pembayaran Gagal/Kedaluwarsa
                    $pesanan->update(['status_pembayaran' => 'gagal']);
                }
            }
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'invalid signature'], 403);
    }
}