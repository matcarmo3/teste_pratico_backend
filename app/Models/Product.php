<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'amount',
        'created_at',
        'updated_at',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function total($amount)
    {
        return number_format($this->price * $amount, 2, '.', '');
    }
}
