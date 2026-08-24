<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    /**
     * Relasi Many-to-Many ke Company
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_department');
    }

    public function pics(): HasMany
    {
        return $this->hasMany(Pic::class, 'department_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'department_id');
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
