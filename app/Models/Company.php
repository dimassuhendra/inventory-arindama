<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    protected $fillable = ['code', 'name'];

    /**
     * Relasi Many-to-Many ke Department
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'company_department');
    }
}
