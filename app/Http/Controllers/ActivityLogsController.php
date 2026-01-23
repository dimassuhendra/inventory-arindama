<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    public function index()
    {
        $logs = ActivityLogs::with('user')->latest()->paginate(10);
        return view('activity-log', compact('logs'));
    }

    public function destroy($id)
    {
        ActivityLogs::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Log berhasil dihapus.');
    }
}