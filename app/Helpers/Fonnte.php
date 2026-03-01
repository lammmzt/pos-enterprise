<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fonnte
{
    /**
     * Mengirim pesan WhatsApp via Fonnte
     * * @param string|array $target (Nomor HP tujuan, contoh: '08123456789')
     * @param string $message (Isi pesan)
     * @return array|bool
     */
    public static function send($target, $message)
    {
        $token = env('FONNTE_TOKEN');

        if (!$token) {
            Log::error('Fonnte Token tidak ditemukan di .env');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default Indonesia
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }
}