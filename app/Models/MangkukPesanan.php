<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangkukPesanan extends Model
{
    protected $table = 'mangkuk_pesanan';
    protected $primaryKey = 'id_mangkuk';
    protected $guarded = [];

    public function pesanan() { 
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
    
    public function detailPesanan() { 
        return $this->hasMany(DetailPesanan::class, 'id_mangkuk', 'id_mangkuk'); 
    }
}