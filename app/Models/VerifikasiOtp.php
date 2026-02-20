<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiOtp extends Model
{
    protected $table = 'verifikasi_otp';
    protected $primaryKey = 'id_otp';

    protected $fillable = [
        'id_user',
        'kode_otp',
        'tipe',
        'waktu_kedaluwarsa',
        'status_terpakai',
    ];

    protected function casts(): array
    {
        return [
            'waktu_kedaluwarsa' => 'datetime',
            'status_terpakai' => 'boolean',
        ];
    }

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}