<?php

namespace App\Observers;

use App\Models\ActivityLogs;
use Illuminate\Database\Eloquent\Model;

class LogObserver
{
    // Otomatis mencatat saat data baru dibuat (Stock In, Order, dll)
    public function created(Model $model)
    {
        $this->record($model, 'Membuat Data Baru');
    }

    // Otomatis mencatat saat data diubah
    public function updated(Model $model)
    {
        $changes = [
            'before' => array_intersect_key($model->getOriginal(), $model->getDirty()),
            'after' => $model->getDirty()
        ];

        $this->record($model, 'Mengubah Data', $changes);
    }

    // Otomatis mencatat saat data dihapus
    public function deleted(Model $model)
    {
        $this->record($model, 'Menghapus Data', $model->toArray());
    }

    // Fungsi internal untuk menyimpan ke tabel activity_logs
    protected function record(Model $model, $action, $properties = null)
    {
        if ($model instanceof ActivityLog)
            return;

        ActivityLogs::create([
            'user_id' => auth()->id() ?? 1, // Default ke ID 1 jika sistem/console
            'activity' => $action . " pada " . class_basename($model),
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}