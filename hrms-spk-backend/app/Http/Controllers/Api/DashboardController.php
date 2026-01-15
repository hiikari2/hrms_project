<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\AhpSession;
use App\Models\AhpResult;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'aktif')->count();
        $totalSessions = AhpSession::count();
        $completedSessions = AhpSession::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_employees' => $totalEmployees,
                'active_employees' => $activeEmployees,
                'total_sessions' => $totalSessions,
                'completed_sessions' => $completedSessions
            ]
        ]);
    }

    public function getCharts()
    {
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}