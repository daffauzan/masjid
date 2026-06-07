<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'status',
        'transaction_reference',
        'paid_at',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
