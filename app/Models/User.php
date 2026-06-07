<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $nama
 * @property string $email
 * @property string $password
 * @property string $no_telp
 * @property string $role
 */
class user extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'password',
        'email',
        'no_telp',
        'role',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function zakat(){
        return $this->hasMany(Zakat::class, 'id_user');
    }
}
