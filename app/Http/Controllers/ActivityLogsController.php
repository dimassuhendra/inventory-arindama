<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogsService;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    protected ActivityLogsService $activityLogsService;

    public function __construct(ActivityLogsService $activityLogsService)
    {
        $this->activityLogsService = $activityLogsService;
    }

    public function index(Request $request)
    {
        $data = $this->activityLogsService->getActivityLogPageData($request);
        $data['pageTitle'] = 'System Activity Log & Audit Trail';

        return view('activity-log', $data);
    }

    public function destroy($id)
    {
        try {
            \App\Models\ActivityLogs::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Baris log berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    public function prune(Request $request)
    {
        try {
            $days = $request->input('days', 30);
            $deletedCount = $this->activityLogsService->pruneLogs((int)$days);

            return redirect()->back()->with('success', "Pembersihan berhasil! {$deletedCount} catatan log yang berusia lebih dari {$days} hari telah dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan log: ' . $e->getMessage());
        }
    }
}
