<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'admin_id',
        'customer_name',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_method',
        'order_number',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(admin::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function zakatTransaksi()
    {
        return $this->hasOne(Transaksi::class, 'order_id');
    }
}
