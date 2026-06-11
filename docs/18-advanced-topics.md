# Module 18: Advanced Topics & Architecture

**Duration:** Ongoing | **Level:** Advanced

---

## Service Layer Pattern

Keep controllers thin; business logic in services:

```
app/
├── Services/
│   ├── TaskService.php
│   └── DashboardService.php
├── Repositories/          # Optional data access layer
│   └── TaskRepository.php
└── Actions/               # Single-purpose classes
    └── CompleteTask.php
```

```php
class CompleteTask
{
    public function execute(Task $task, User $user): Task
    {
        $task->markComplete();
        event(new TaskCompleted($task, $user));
        return $task;
    }
}
```

---

## Repository Pattern

Abstract database access:

```php
interface TaskRepositoryInterface
{
    public function findOverdue(): Collection;
}

class EloquentTaskRepository implements TaskRepositoryInterface { ... }
```

Bind in `AppServiceProvider`:

```php
$this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
```

---

## Multi-Tenancy

For SaaS apps serving multiple organizations:

- [stancl/tenancy](https://github.com/archtechx/tenancy)
- Database-per-tenant or shared database with `tenant_id`

---

## Microservices vs Monolith

Laravel excels as a **modular monolith**:

```
app/
├── Domains/
│   ├── Tasks/
│   │   ├── Models/
│   │   ├── Controllers/
│   │   └── Services/
│   └── Billing/
```

Extract to microservices only when you have clear scaling boundaries.

---

## Laravel Packages Ecosystem

| Package | Purpose |
|---------|---------|
| `spatie/laravel-permission` | Roles & permissions |
| `spatie/laravel-medialibrary` | Media management |
| `spatie/laravel-activitylog` | Audit logging |
| `laravel/scout` | Full-text search (Algolia/Meilisearch) |
| `laravel/cashier` | Stripe subscriptions |
| `laravel/socialite` | OAuth login |
| `barryvdh/laravel-debugbar` | Dev debugging |

---

## Design Patterns in Laravel

| Pattern | Laravel Feature |
|---------|-----------------|
| Factory | Model factories |
| Observer | Model observers |
| Strategy | Driver-based systems (cache, mail) |
| Decorator | Middleware |
| Command | Artisan commands, Jobs |
| Repository | Custom implementations |

---

## API-First & Mobile

1. Build API with Sanctum/Passport
2. Use API Resources for consistent JSON
3. Version your API (`/api/v1/`)
4. Document with [Scramble](https://scramble.dedoc.co/) or OpenAPI

---

## Continuing Your Journey

### Build Portfolio Projects

1. **Blog CMS** — posts, categories, comments, admin panel
2. **E-commerce** — products, cart, Stripe checkout
3. **SaaS Dashboard** — subscriptions, teams, billing
4. **Real-time Chat** — broadcasting, presence channels

### Certifications & Community

- Laravel certification (official)
- Laracasts learning paths
- Laravel Live conferences
- Contribute to open source

### Stay Current

- Read [Laravel News](https://laravel-news.com)
- Follow release notes each major version
- Upgrade guide: `laravel.com/docs/upgrade`

---

## Congratulations!

You've completed the TaskForge Laravel curriculum from setup through production. You now have:

- 19 learning modules (00–18)
- A full-featured example application
- Automated test suite
- Deployment configurations
- Real-world patterns used in professional Laravel development

**Keep building. Keep testing. Keep shipping.**

---

## Curriculum Complete

Return to [README](../README.md) for the full roadmap and progress checklist.
