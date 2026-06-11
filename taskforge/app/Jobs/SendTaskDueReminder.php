<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskDueReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendTaskDueReminder implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task,
    ) {}

    public function handle(): void
    {
        $recipients = $this->task->assignees->push($this->task->creator)->unique('id');

        Notification::send($recipients, new TaskDueReminder($this->task));
    }
}
