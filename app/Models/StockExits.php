<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockExits extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'description', 'exit_date'];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}