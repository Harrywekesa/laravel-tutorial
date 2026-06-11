# Module 11: Queues, Jobs & Scheduling

**Duration:** 4–5 days | **Level:** Intermediate–Advanced

---

## Why Queues?

Offload slow work (emails, API calls, image processing) to background workers so HTTP responses stay fast.

---

## Creating Jobs

```bash
php artisan make:job SendTaskDueReminder
```

```php
class SendTaskDueReminder implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        Notification::send($recipients, new TaskDueReminder($this->task));
    }
}
```

**Dispatch:**

```php
SendTaskDueReminder::dispatch($task);
SendTaskDueReminder::dispatch($task)->delay(now()->addMinutes(5));
```

---

## Queue Configuration

```env
QUEUE_CONNECTION=database   # Good for learning
# QUEUE_CONNECTION=redis      # Production recommended
```

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
php artisan queue:work --tries=3 --timeout=60
```

---

## Task Scheduler

`routes/console.php`:

```php
Schedule::command('tasks:send-reminders')->dailyAt('08:00');
```

**Cron entry (production):**

```cron
* * * * * cd /var/www/taskforge && php artisan schedule:run >> /dev/null 2>&1
```

**Local testing:**

```bash
php artisan schedule:work
php artisan tasks:send-reminders
```

---

## Failed Jobs

```bash
php artisan queue:failed
php artisan queue:retry all
```

---

## TaskForge Files

- `app/Jobs/SendTaskDueReminder.php`
- `app/Console/Commands/SendDueReminders.php`
- `app/Notifications/TaskDueReminder.php` (also queued)

---

## Exercises

1. Create `ProcessAttachment` job for virus scanning simulation.
2. Configure `QUEUE_CONNECTION=sync` vs `database` and compare behavior.
3. Add retry logic with `$tries = 3` and `$backoff = [10, 60, 300]`.

---

## Next Module

→ [Module 12: Events & Listeners](12-events-listeners.md)
