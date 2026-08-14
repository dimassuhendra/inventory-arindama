<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartRequest extends Model
{
    protected $fillable = ['reference_number', 'user_id', 'status', 'purpose'];

    public function items()
    {
        return $this->hasMany(CartRequestItems::class);
    }

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    /**
     * Relasi ke Products melalui CartRequestItems
     */
    public function products()
    {
        return $this->hasManyThrough(
            Products::class,
            CartRequestItems::class,
            'cart_request_id', // Foreign key di tabel cart_request_items
            'id',              // Foreign key di tabel products
            'id',              // Local key di tabel cart_requests
            'product_id'       // Local key di tabel cart_request_items
        );
    }
}
