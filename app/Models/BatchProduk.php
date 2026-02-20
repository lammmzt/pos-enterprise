<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchProduk extends Model
{
    protected $table = 'batch_produk';
    protected $primaryKey = 'id_batch';
    protected $guarded = [];
    protected $fillable = [
        'id_produk',
        'id_pembelian',
        'jumlah',
        'harga_beli',
        'tanggal_kedaluwarsa',
    ];
    
    // Relasi
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'id_batch', 'id_batch');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_batch', 'id_batch');
    }
}