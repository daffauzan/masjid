<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'password',
        'email',
    ];
    protected $hidden = [
        password,
    ];

    public function zakat(){
        return $this->hasMany(Zakat::class, 'id_user');
    }
}
