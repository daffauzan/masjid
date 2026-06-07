<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class zakat extends Model
{
    protected $table = 'zakat';

    protected $fillable = [
        'id_user',
        'admin_id',
        'nama_zakat',
        'kategori',
        'jumlah_jiwa',
        'keterangan',
        'jumlah',
        'tanggal',
    ];

    public function user(){
        return $this->belongsTo(user::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'zakat_id');
    }
}
