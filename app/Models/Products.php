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
        'first_used_at'              => 'date',
        'purchase_date'              => 'date',
        'last_maintenance_date'      => 'date',
        'purchase_cost'              => 'decimal:2',
        'residual_value'             => 'decimal:2',
        'useful_life_years'          => 'integer',
        'maintenance_frequency_days' => 'integer',
    ];

    // RELASI TAMBAHAN
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function pic()
    {
        return $this->belongsTo(Pic::class, 'pic_id');
    }

    /**
     * HELPER AKUNTANSI: Hitung Nilai Buku Aset Saat Ini (Depresiasi Garis Lurus)
     */
    public function getCurrentBookValueAttribute(): float
    {
        if (!$this->purchase_cost || !$this->useful_life_years || !$this->purchase_date) {
            return (float) $this->purchase_cost;
        }

        $cost = (float) $this->purchase_cost;
        $residual = (float) $this->residual_value;
        $usefulLife = (int) $this->useful_life_years;

        $depreciableAmount = $cost - $residual;
        $annualDepreciation = $depreciableAmount / $usefulLife;

        $yearsPassed = $this->purchase_date->diffInDays(now()) / 365;

        if ($yearsPassed >= $usefulLife) {
            return $residual;
        }

        $currentValue = $cost - ($annualDepreciation * $yearsPassed);
        return max($currentValue, $residual);
    }

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
