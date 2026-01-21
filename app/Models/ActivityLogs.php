<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'model_type',
        'model_id',
        'properties',
        'ip_address',
        'user_agent'
    ];

    // Penting: Mengubah JSON di DB menjadi Array PHP secara otomatis
    protected $casts = [
        'properties' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    /**
     * Helper Static untuk mencatat log dengan schema Anda
     */
    public static function log($activity, $model = null, $properties = null)
    {
        self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}