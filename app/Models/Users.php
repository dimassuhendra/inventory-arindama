<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Users extends Authenticatable
{
    use Notifiable, HasRoles;

    // Memastikan Eloquent mengacu pada tabel 'users'
    protected $table = 'users';

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Log Aktivitas
    public function activityLogs()
    {
        return $this->hasMany(ActivityLogs::class, 'user_id');
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntries::class, 'user_id');
    }

    public function stockExits()
    {
        return $this->hasMany(StockExits::class, 'user_id');
    }

    // ➕ RELASI BARU: Supplier yang dibuat oleh User ini
    public function suppliers()
    {
        return $this->hasMany(Suppliers::class, 'user_id');
    }

    // ➕ RELASI BARU: Kategori yang dibuat oleh User ini
    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id');
    }
}
