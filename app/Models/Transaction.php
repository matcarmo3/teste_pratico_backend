<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'external_id',
        'user_id',
        'gateway_id',
        'total',
        'card_last_numbers',
        'status',
    ];

    protected $with = ['products'];

    public function getProductsAttribute()
    {
        return $this->productsRelation->map(function ($product) {
            return [
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price
            ];
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_products')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
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
