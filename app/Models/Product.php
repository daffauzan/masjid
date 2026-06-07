<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'active',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
