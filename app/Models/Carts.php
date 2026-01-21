<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    protected $fillable = ['user_id', 'item_name', 'quantity', 'status'];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}