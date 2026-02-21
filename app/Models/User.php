<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// Tambahan untuk Hak Akses & Filament
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName; // <-- 1. Tambahkan import ini
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasName // <-- Implementasikan FilamentUser
{
    use HasFactory, Notifiable, HasRoles; // <-- Tambahkan HasRoles

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'alamat',
        // 'role', // Ini kolom enum dari migration kamu
        'status',
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

    public function getFilamentName(): string
    {
        // Beri tahu Filament untuk memakai kolom 'nama' dari database kita
        return $this->nama; 
    }
}