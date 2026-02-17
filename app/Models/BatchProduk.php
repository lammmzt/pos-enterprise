<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchProduk extends Model
{
    protected $table = 'batch_produk';
    protected $primaryKey = 'id_batch';
    protected $guarded = [];

    public function produk() {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function pembelian() {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }
}