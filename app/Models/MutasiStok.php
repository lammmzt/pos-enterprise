<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';
    protected $guarded = [];

    public function produk() {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function batchProduk() {
        return $this->belongsTo(BatchProduk::class, 'id_batch', 'id_batch');
    }

    public function pengguna() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}