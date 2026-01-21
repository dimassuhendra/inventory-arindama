<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntries extends Model
{
    protected $fillable = ['product_id', 'supplier_id', 'user_id', 'quantity', 'entry_date'];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}