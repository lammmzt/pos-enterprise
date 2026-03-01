<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $guarded = [];
    protected $fillable = [
        'id_user',
        'id_kasir',
        'nomor_invoice',
        'total_harga',
        'status_pembayaran',
        'status_pesanan',
        'tipe_pesanan',
        'metode_pembayaran',
        'link_delivery',
        'catatan',
    ];
    // Relasi
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id_user');
    }
    public function mangkuk() {
         return $this->hasMany(MangkukPesanan::class, 'id_pesanan', 'id_pesanan'); 
    }

    public function testimoni()
    {
        return $this->hasOne(Testimoni::class, 'id_pesanan', 'id_pesanan');
    }
}