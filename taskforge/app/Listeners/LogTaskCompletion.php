<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Models\ActivityLog;

class LogTaskCompletion
{
    public function handle(TaskCompleted $event): void
    {
        ActivityLog::record(
            action: 'task.completed',
            subject: $event->task,
            user: $event->completedBy,
            properties: [
                'title' => $event->task->title,
                'project' => $event->task->project->name,
            ],
        );
    }
}
