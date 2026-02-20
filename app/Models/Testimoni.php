<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $table = 'testimoni';
    protected $primaryKey = 'id_testimoni';
    protected $guarded = [];
    protected $fillable = [
        'id_pesanan',
        'rating',
        'ulasan',
        'status_tampil',
    ];
    // Ubah dari belongsTo User menjadi belongsTo Pesanan
    protected function casts(): array
    {
        return [
            'status_tampil' => 'boolean',
        ];
    }

    // Relasi
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}