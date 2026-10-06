<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shopping_carts';

    protected $fillable = [
        'user_id',
        'total_price',
        'paid',
        'cart_items',
    ];

    protected $casts = [
        'cart_items' => 'array',
        'total_price' => 'decimal:2',
    ];
}
