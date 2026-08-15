<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suppliers extends Model
{
    protected $fillable = ['user_id', 'name', 'telp', 'address'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Products::class, 'supplier_id');
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntries::class, 'supplier_id');
    }
}
