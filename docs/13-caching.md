# Module 13: Caching & Performance

**Duration:** 3–4 days | **Level:** Advanced

---

## Cache Drivers

```env
CACHE_STORE=database   # Learning
# CACHE_STORE=redis    # Production
```

```bash
php artisan cache:table && php artisan migrate
```

---

## Basic Cache Operations

```php
Cache::put('key', 'value', now()->addMinutes(10));
$value = Cache::get('key', 'default');
Cache::forget('key');
Cache::flush();
```

---

## Remember Pattern (TaskForge DashboardService)

```php
return Cache::remember(
    "dashboard.stats.{$user->id}",
    now()->addMinutes(5),
    fn () => $this->computeStats($user),
);
```

Invalidate when data changes:

```php
Cache::forget("dashboard.stats.{$user->id}");
```

---

## Query Optimization

1. **Eager loading:** `with(['project', 'assignees'])`
2. **Select only needed columns:** `select(['id', 'title'])`
3. **Chunk large datasets:** `Task::chunk(100, fn ($tasks) => ...)`
4. **Database indexes** on frequently queried columns

---

## Laravel Telescope (Debugging)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
```

Monitors queries, requests, jobs, cache, mail.

---

## Production Performance Checklist

- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan event:cache`
- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] Redis for cache, sessions, queues
- [ ] OPcache enabled on PHP

---

## Exercises

1. Cache project list for 10 minutes per user.
2. Use `Cache::tags()` (Redis only) for grouped invalidation.
3. Profile a page with Telescope and fix one N+1 query.

---

## Next Module

→ [Module 14: Testing](14-testing.md)
