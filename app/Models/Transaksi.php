<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'order_id',
        'zakat_id',
        'id_user',
        'admin_id',
        'jumlah_bayar',
        'metode_pembayaran',
        'status',
        'nomor_transaksi',
        'tanggal_bayar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function zakat()
    {
        return $this->belongsTo(zakat::class, 'zakat_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'id_user');
    }
}
