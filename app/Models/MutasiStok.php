<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';
    protected $guarded = [];
    protected $fillable = [
        'id_produk',
        'id_batch',
        'id_user',
        'tipe',
        'jumlah',
        'tipe_referensi',
        'id_referensi',
        'catatan',
    ];
    
    // Relasi
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function batchProduk()
    {
        return $this->belongsTo(BatchProduk::class, 'id_batch', 'id_batch');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}