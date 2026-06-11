<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = $this->dashboardService->stats($user);

        $recentTasks = Task::forUser($user)
            ->with(['project', 'assignees'])
            ->latest()
            ->limit(10)
            ->get();

        $overdueTasks = Task::forUser($user)
            ->overdue()
            ->with('project')
            ->limit(5)
            ->get();

        $activity = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentTasks', 'overdueTasks', 'activity'));
    }
}
