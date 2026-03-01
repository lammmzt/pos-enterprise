<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'alamat',
        'role', // Ini kolom enum dari migration kamu
        'status',
        'no_hp',
        'catatan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // --- Konfigurasi Akses Panel Filament ---
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya yang statusnya 'aktif' yang bisa login
        if ($this->status !== 'aktif') {
            return false;
        }

        // Pelanggan dan Antrean TIDAK BOLEH masuk ke admin panel (back-office)
        if (in_array($this->role, ['pelanggan', 'antrean'])) {
            return false;
        }

        // Owner, Admin, dan Kasir diizinkan masuk
        return true;
    }

    // --- Relasi ---
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'id_user', 'id_user');
    }

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'id_user', 'id_user');
    }

    public function pesananSebagaiPelanggan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function pesananSebagaiKasir()
    {
        return $this->hasMany(Pesanan::class, 'id_kasir', 'id_user');
    }


    public function verifikasiOtp()
    {
        return $this->hasMany(VerifikasiOtp::class, 'id_user', 'id_user');
    }

}