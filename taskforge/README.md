# TaskForge — Laravel Learning Example Project

A team task management application built to demonstrate every major Laravel concept from the [learning curriculum](../README.md).

**Stack:** Laravel 12 · PHP 8.2+ · SQLite/MySQL · Sanctum · Tailwind CSS · Vite

---

## Quick Start

```bash
# From taskforge directory
copy .env.example .env
php artisan key:generate

# SQLite (default — easiest)
# Ensure database/database.sqlite exists, or:
type nul > database\database.sqlite

php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Visit **http://localhost:8000**

| Account | Email | Password |
|---------|-------|----------|
| Admin | admin@taskforge.test | password |
| Member | member@taskforge.test | password |

---

## Features & Laravel Concepts

| Feature | Files to Study | Module |
|---------|----------------|--------|
| User auth (login/register) | `AuthController`, `auth/*` views | 06 |
| Dashboard with cached stats | `DashboardService`, `DashboardController` | 13 |
| Project CRUD | `ProjectController`, `ProjectPolicy` | 03, 05 |
| Task CRUD + filters | `TaskController`, `Task` model scopes | 03, 05 |
| Task assignments (M2M) | `task_user` migration, `assignees()` | 05 |
| Comments | `Comment` model, `storeComment()` | 05 |
| File attachments | `Attachment`, `storeAttachment()` | 10 |
| Form validation | `StoreTaskRequest`, etc. | 09 |
| Authorization policies | `TaskPolicy`, `ProjectPolicy` | 06 |
| Events & activity log | `TaskCompleted`, `ActivityLog` | 12 |
| Queued notifications | `SendTaskDueReminder`, `TaskDueReminder` | 11, 12 |
| REST API (Sanctum) | `TaskApiController`, `TaskResource` | 07 |
| Scheduled commands | `SendDueReminders`, `routes/console.php` | 11 |
| PHPUnit tests | `tests/Feature/*` | 14 |
| PHP Enums | `app/Enums/*` | 01 |

---

## Project Structure

```
app/
├── Console/Commands/SendDueReminders.php
├── Enums/                    # TaskStatus, TaskPriority, UserRole
├── Events/TaskCompleted.php
├── Http/
│   ├── Controllers/          # Web controllers
│   ├── Controllers/Api/      # API controllers
│   ├── Requests/             # Form validation
│   └── Resources/            # API JSON transformers
├── Jobs/SendTaskDueReminder.php
├── Listeners/LogTaskCompletion.php
├── Models/                   # Eloquent models
├── Notifications/TaskDueReminder.php
├── Policies/                 # Authorization
└── Services/DashboardService.php
database/
├── migrations/
├── factories/
└── seeders/DatabaseSeeder.php
resources/views/
├── layouts/app.blade.php
├── auth/
├── projects/
├── tasks/
└── dashboard.blade.php
routes/
├── web.php
├── api.php
└── console.php
tests/
├── Feature/AuthTest.php
├── Feature/TaskTest.php
└── Feature/Api/TaskApiTest.php
deploy/
├── nginx.conf
├── supervisor.conf
└── github-actions.yml
```

---

## Common Commands

```bash
php artisan serve                    # Dev server
php artisan test                     # Run tests
php artisan queue:work               # Process jobs
php artisan schedule:work            # Run scheduler locally
php artisan tasks:send-reminders     # Manual due-date reminders
php artisan route:list               # All routes
php artisan tinker                   # REPL
npm run dev                          # Frontend assets (dev)
npm run build                        # Frontend assets (prod)
```

---

## API Usage

Create a token in Tinker:

```php
$user = User::where('email', 'admin@taskforge.test')->first();
$token = $user->createToken('api')->plainTextToken;
```

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Accept: application/json" \
     http://localhost:8000/api/tasks
```

---

## Running Tests

```bash
php artisan test
# 14 tests covering auth, tasks, and API
```

---

## Deployment

See [deploy/](deploy/) for Nginx, Supervisor, and GitHub Actions configs.
Full deployment guide: [docs/16-deployment-hosting.md](../docs/16-deployment-hosting.md)

---

## Learning Path

Work through modules in order, implementing each feature as you go:

1. **Modules 00–02** — Setup, PHP, lifecycle
2. **Modules 03–06** — Routes, views, database, auth
3. **Modules 07–11** — API, assets, validation, storage, queues
4. **Modules 12–15** — Events, cache, testing, security
5. **Modules 16–18** — Deploy, monitor, advanced architecture

Start here: [docs/00-getting-started.md](../docs/00-getting-started.md)
