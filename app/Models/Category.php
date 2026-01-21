<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    /**
     * Boot function untuk menghandle otomatisasi slug saat nama diisi.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Relasi ke Model Products (Satu kategori memiliki banyak produk)
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
}