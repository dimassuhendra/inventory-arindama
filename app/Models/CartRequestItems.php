<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartRequestItems extends Model
{
    protected $fillable = ['cart_request_id', 'product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
