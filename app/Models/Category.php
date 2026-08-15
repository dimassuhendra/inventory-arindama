<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'slug',
        'name',
        'allowed_roles',
    ];

    protected $casts = [
        'allowed_roles' => 'array',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relasi Sub Kategori Anak
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name', 'asc');
    }

    /**
     * Relasi ke Model Products (Satu kategori memiliki banyak produk)
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
}
