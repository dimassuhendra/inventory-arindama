<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'slug',
        'description',
        'quantity',
        'image',
        'unit',
        'first_used_at'
    ];

    protected $casts = [
        'first_used_at' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function entries()
    {
        return $this->hasMany(StockEntries::class, 'product_id');
    }

    public function exits()
    {
        return $this->hasMany(StockExits::class, 'product_id');
    }
}
