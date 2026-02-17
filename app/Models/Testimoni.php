<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $table = 'testimoni';
    protected $primaryKey = 'id_testimoni';
    protected $guarded = [];

    public function pengguna() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}