<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    protected $table = 'transaction_products';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'price',
        'quantity',
    ];
}
