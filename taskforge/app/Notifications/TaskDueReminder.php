<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Due Soon: '.$this->task->title)
            ->line('The task "'.$this->task->title.'" is due on '.$this->task->due_at->format('M j, Y').'.')
            ->action('View Task', url('/tasks/'.$this->task->id))
            ->line('Stay on top of your work!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_at' => $this->task->due_at?->toIso8601String(),
        ];
    }
}
