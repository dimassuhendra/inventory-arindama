<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Users extends Authenticatable
{
    use Notifiable, HasRoles;

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
}