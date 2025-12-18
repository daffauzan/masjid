<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'username',
        'email',
        'password',
        'no_telp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function konten(){
        return $this->hasMany(Konten::class, 'id_admin');
    }

}
