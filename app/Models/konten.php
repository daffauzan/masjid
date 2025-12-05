<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class konten extends Model
{
    protected $table = 'konten';

    protected $fillable = [
        'judul',
        'konten',
        'kategori',
        'gambar',
        'file',
        'id_admin',
        'tanggal',
    ];

    public function admin() {
        return $this->belongsTo(admin::class, 'id_admin');
    } 
}
