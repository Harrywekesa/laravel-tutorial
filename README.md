# Laravel Complete Learning Guide & Curriculum

A structured path from **absolute beginner** to **production-ready Laravel developer**, with hands-on notes and the **TaskForge** example project.

> **Your environment:** PHP 8.2 + Laravel 12 (TaskForge). Laravel 13 requires PHP 8.3+.

---

## How to Use This Guide

1. **Read modules in order** — each builds on the previous (`docs/00` → `docs/18`).
2. **Code along in TaskForge** — run commands and edit files as each module instructs.
3. **Complete checkpoints** — every module ends with exercises and a self-check quiz.
4. **Track progress** — use the checklist below.

---

## Curriculum Roadmap (≈ 16–24 weeks at 10–15 hrs/week)

| Phase | Weeks | Modules | Goal |
|-------|-------|---------|------|
| **Foundation** | 1–3 | 00–02 | PHP, tooling, Laravel anatomy |
| **Core Web** | 4–7 | 03–06 | Routes, views, DB, auth |
| **Intermediate** | 8–11 | 07–11 | APIs, assets, validation, storage, queues |
| **Advanced** | 12–14 | 12–15 | Events, cache, testing, security |
| **Production** | 15–18 | 16–18 | Deploy, monitor, maintain, scale |

---

## Progress Checklist

### Phase 1 — Foundation
- [ ] Module 00: Environment & first Laravel app
- [ ] Module 01: PHP essentials for Laravel
- [ ] Module 02: Request lifecycle & project structure

### Phase 2 — Core Web Development
- [ ] Module 03: Routing & controllers
- [ ] Module 04: Blade templates & layouts
- [ ] Module 05: Database, migrations & Eloquent
- [ ] Module 06: Authentication & authorization

### Phase 3 — Intermediate Skills
- [ ] Module 07: REST API development
- [ ] Module 08: Frontend assets (Vite, Tailwind)
- [ ] Module 09: Validation, forms & requests
- [ ] Module 10: File storage & media
- [ ] Module 11: Queues, jobs & scheduling

### Phase 4 — Advanced & Production
- [ ] Module 12: Events, listeners & notifications
- [ ] Module 13: Caching & performance
- [ ] Module 14: Testing (unit, feature, browser)
- [ ] Module 15: Security hardening
- [ ] Module 16: Deployment & hosting
- [ ] Module 17: Maintenance & monitoring
- [ ] Module 18: Advanced patterns & architecture

---

## PDF Copies

Printable PDF versions of all modules are in **`docs/pdf/`**:

- Individual modules: `00-getting-started.pdf` … `18-advanced-topics.pdf`
- Curriculum overview: `00-curriculum-overview.pdf`
- TaskForge project guide: `taskforge-readme.pdf`
- **All-in-one:** `Laravel-Complete-Learning-Guide.pdf`

To regenerate PDFs after editing markdown:

```bash
python scripts/md_to_pdf.py
```

---

## Repository Structure

```
laravel/
├── README.md                 ← You are here (curriculum overview)
├── docs/                     ← Detailed learning modules (00–18)
│   └── pdf/                  ← PDF copies of all modules
│   ├── 00-getting-started.md
│   ├── 01-php-fundamentals.md
│   └── ...
└── taskforge/                ← Example project (task management app)
    ├── README.md             ← Project-specific setup & feature map
    ├── app/                  ← Application code (models, controllers, etc.)
    ├── database/             ← Migrations, seeders, factories
    ├── resources/views/      ← Blade templates
    ├── routes/               ← Web & API routes
    ├── tests/                ← PHPUnit tests
    └── deploy/               ← Deployment configs & scripts
```

---

## TaskForge — Example Project

**TaskForge** is a team task management application that demonstrates every major Laravel concept:

| Feature | Laravel Concepts |
|---------|------------------|
| User registration & login | Breeze, middleware, policies |
| Projects & tasks CRUD | Resource controllers, Eloquent relationships |
| Task assignments & comments | Many-to-many, polymorphic relations |
| Due-date reminders | Queued jobs, mail, scheduling |
| Activity log | Events, listeners, observers |
| REST API | API resources, Sanctum tokens |
| File attachments | Storage, validation |
| Dashboard stats | Query scopes, caching |
| Full test suite | Feature & unit tests |

### Quick Start

```bash
cd taskforge
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Visit `http://localhost:8000` — login with `admin@taskforge.test` / `password`.

See [taskforge/README.md](taskforge/README.md) for the full feature map and module cross-references.

---

## Recommended Learning Schedule

### Daily (1–2 hours)
- 30 min reading the module
- 45 min coding in TaskForge
- 15 min review & notes

### Weekly
- Complete 1 module
- Run `php artisan test` after each module
- Push a git commit summarizing what you learned

### Monthly Milestones
| Month | Milestone |
|-------|-----------|
| 1 | TaskForge CRUD + auth working locally |
| 2 | API endpoints + validation + file uploads |
| 3 | Queues, events, 80%+ test coverage |
| 4 | Deployed to staging with CI/CD |

---

## Essential Resources

| Resource | URL |
|----------|-----|
| Official Laravel Docs | https://laravel.com/docs |
| Laracasts (video) | https://laracasts.com |
| Laravel News | https://laravel-news.com |
| PHP The Right Way | https://phptherightway.com |

---

## Next Step

Open **[docs/00-getting-started.md](docs/00-getting-started.md)** and begin Module 00.
