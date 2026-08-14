<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'company_name',
    ];

    /**
     * Relasi ke PIC (Satu departemen memiliki banyak PIC)
     */
    public function pics(): HasMany
    {
        return $this->hasMany(Pic::class, 'department_id');
    }

    /**
     * Relasi ke Products/Aset (Satu departemen ditempati banyak aset)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'department_id');
    }
}
