<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pic extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'nip',
        'name',
        'position',
        'phone',
        'email',
        'company_name',
    ];

    /**
     * Relasi ke Department (Setiap PIC terikat pada satu departemen)
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relasi ke Products/Aset (Satu PIC dapat bertanggung jawab atas banyak aset)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'pic_id');
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
