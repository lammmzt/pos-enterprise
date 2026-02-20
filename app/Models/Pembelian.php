<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];
    protected $fillable = [
        'id_pemasok',
        'id_user',
        'nomor_referensi',
        'total_harga',
        'status',
        'tanggal_pembelian',
    ];
    
    // Relasi
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok', 'id_pemasok');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function batchProduk()
    {
        return $this->hasMany(BatchProduk::class, 'id_pembelian', 'id_pembelian');
    }
}