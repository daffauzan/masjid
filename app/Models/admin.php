<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'username',
        'password',
        'email',
        'no_telp',
    ];

    protected $hidden = [
        'password',
    ];

    public function konten(){
        return $this->hasMany(Konten::class, 'id_admin');
    }

}
