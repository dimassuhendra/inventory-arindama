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
}
