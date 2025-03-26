<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'external_id',
        'user_id',
        'product_id',
        'gateway_id',
        'price',
        'quantity',
        'total',
        'card_last_numbers',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
