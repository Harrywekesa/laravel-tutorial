<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function stats(User $user): array
    {
        return Cache::remember(
            "dashboard.stats.{$user->id}",
            now()->addMinutes(5),
            fn () => $this->computeStats($user),
        );
    }

    public function clearCache(User $user): void
    {
        Cache::forget("dashboard.stats.{$user->id}");
    }

    private function computeStats(User $user): array
    {
        $baseQuery = Task::forUser($user);

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', TaskStatus::Pending)->count(),
            'in_progress' => (clone $baseQuery)->where('status', TaskStatus::InProgress)->count(),
            'completed' => (clone $baseQuery)->where('status', TaskStatus::Completed)->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];
    }
}
