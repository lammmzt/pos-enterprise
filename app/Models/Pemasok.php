<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    protected $table = 'pemasok';
    protected $primaryKey = 'id_pemasok';
    protected $guarded = [];
    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'alamat',
    ];
    
    public function pembelian() {
        return $this->hasMany(Pembelian::class, 'id_pemasok', 'id_pemasok');
    }
}