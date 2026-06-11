<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Jobs\SendTaskDueReminder;
use App\Models\Task;
use Illuminate\Console\Command;

class SendDueReminders extends Command
{
    protected $signature = 'tasks:send-reminders';

    protected $description = 'Send reminders for tasks due within 24 hours';

    public function handle(): int
    {
        $tasks = Task::whereNotIn('status', [TaskStatus::Completed->value, TaskStatus::Cancelled->value])
            ->whereBetween('due_at', [now(), now()->addDay()])
            ->with(['assignees', 'creator'])
            ->get();

        foreach ($tasks as $task) {
            SendTaskDueReminder::dispatch($task);
            $this->info("Queued reminder for: {$task->title}");
        }

        $this->info("Processed {$tasks->count()} tasks.");

        return self::SUCCESS;
    }
}
