<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $guarded = [];

    public function pelanggan() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kasir() {
        return $this->belongsTo(User::class, 'id_kasir', 'id_user');
    }

    public function detailPesanan() {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
}