<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    protected $fillable = ['user_id', 'activity', 'model_type', 'model_id', 'properties', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}