<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    
    
    protected $fillable = [
        'nama',
        'username',
        'alamat',
        'catatan',
        'status',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
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