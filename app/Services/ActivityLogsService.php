<?php

namespace App\Services;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityLogsService
{
    public function getActivityLogPageData(Request $request): array
    {
        $search = $request->input('search');
        $activityType = $request->input('activity_type');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $perPage = $request->input('per_page', 10);

        // 1. Query Utama dengan Eager Loading User
        $query = ActivityLogs::with('user');

        // Filter Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                    ->orWhere('model_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uQ) use ($search) {
                        $uQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Jenis Aktivitas
        if ($activityType) {
            $query->where('activity', 'like', "%{$activityType}%");
        }

        // Filter Rentang Tanggal
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $logs = $query->latest()->paginate($limit)->appends($request->all());

        // Mini Analytics Data
        $totalLogsCount = ActivityLogs::count();
        $todayLogsCount = ActivityLogs::whereDate('created_at', Carbon::today())->count();
        $deleteLogsCount = ActivityLogs::where('activity', 'like', '%hapus%')
            ->orWhere('activity', 'like', '%delete%')
            ->count();

        // User Paling Aktif
        $topUserLog = ActivityLogs::select('user_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->with('user')
            ->first();

        return [
            'logs' => $logs,
            'total_logs_count' => $totalLogsCount,
            'today_logs_count' => $todayLogsCount,
            'delete_logs_count' => $deleteLogsCount,
            'top_user_name' => $topUserLog->user->name ?? '-',
            'top_user_count' => $topUserLog->total ?? 0,
        ];
    }

    public function pruneLogs(int $days = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($days);
        return ActivityLogs::where('created_at', '<', $cutoffDate)->delete();
    }
}
