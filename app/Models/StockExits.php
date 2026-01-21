<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockExits extends Model
{
    use HasFactory;

    protected $table = 'stock_exits';

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'description',
        'exit_date'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}