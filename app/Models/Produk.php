<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $guarded = [];
    protected $fillable = [
        'id_kategori',
        'nama',
        'sku',
        'stok',
        'deskripsi',
        'harga_dasar',
        'harga_jual',
        'gambar',
        'status',
    ];
    
    // Relasi
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function batchProduk()
    {
        return $this->hasMany(BatchProduk::class, 'id_produk', 'id_produk');
    }

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'id_produk', 'id_produk');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'id_produk', 'id_produk');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_produk', 'id_produk');
    }
    
}