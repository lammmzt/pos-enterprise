<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    public function mangkuk() { 
        return $this->belongsTo(MangkukPesanan::class, 'id_mangkuk', 'id_mangkuk'); 
    }
    public function produk() { 
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk'); 
    }
    public function batch() { 
        return $this->belongsTo(BatchProduk::class, 'id_batch', 'id_batch'); 
    }
}