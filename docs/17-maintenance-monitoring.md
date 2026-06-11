# Module 17: Maintenance, Monitoring & Logging

**Duration:** 3–5 days | **Level:** Advanced

---

## Logging

Laravel uses Monolog. Configure in `config/logging.php`:

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug   # production: error or warning
```

**Write logs:**

```php
Log::info('Task created', ['task_id' => $task->id]);
Log::error('Payment failed', ['exception' => $e]);
report($exception);  // Log + optionally send to external service
```

**Log channels:** `single`, `daily`, `slack`, `papertrail`, `syslog`.

---

## Error Tracking

| Service | Integration |
|---------|-------------|
| **Sentry** | `sentry/sentry-laravel` |
| **Flare** | `spatie/laravel-ignition` (included) |
| **Bugsnag** | `bugsnag/bugsnag-laravel` |

```bash
composer require sentry/sentry-laravel
php artisan sentry:publish
```

---

## Application Monitoring

- **Laravel Pulse** — real-time app metrics
- **Laravel Horizon** — Redis queue dashboard
- **New Relic / Datadog** — APM

```bash
composer require laravel/pulse
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"
```

---

## Health Checks

Built-in: `GET /up`

Custom health route:

```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'failed',
        'cache' => Cache::has('health_check') || Cache::put('health_check', true, 60),
    ]);
});
```

---

## Maintenance Mode

```bash
php artisan down --secret="bypass-token"
# Site shows maintenance page; access via /bypass-token

php artisan up
```

**With message:**

```bash
php artisan down --render="errors::503" --retry=60
```

---

## Database Maintenance

```bash
# Backup
php artisan db:backup  # with spatie/laravel-backup

# Manual MySQL dump
mysqldump -u user -p taskforge > backup.sql

# Optimize
php artisan model:prune  # Delete old soft-deleted records
```

### Spatie Backup

```bash
composer require spatie/laravel-backup
```

Schedule daily backups to S3.

---

## Performance Monitoring Checklist

- [ ] Set up error tracking (Sentry)
- [ ] Monitor queue depth and failed jobs
- [ ] Disk space alerts on server
- [ ] Database slow query log
- [ ] Uptime monitoring (UptimeRobot, Pingdom)
- [ ] SSL certificate expiry alerts

---

## Log Rotation

Configure `daily` channel with `days` retention:

```php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => 'debug',
    'days' => 14,
],
```

---

## Exercises

1. Add Sentry or configure Slack log channel for errors.
2. Create custom Artisan command `app:health-check` for monitoring.
3. Set up daily database backup schedule.

---

## Next Module

→ [Module 18: Advanced Topics & Architecture](18-advanced-topics.md)
