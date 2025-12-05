<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class zakat extends Model
{
    protected $table = 'zakat';

    protected $fillable = [
        'jumlah',
        'user_id',
        'admin_id',
        'tanggal',
    ];

    public function user(){
        return $this->belongsTo(user::class, 'id_user');
    }
}
