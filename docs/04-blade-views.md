# Module 04: Blade Templates & Views

**Duration:** 4–5 days | **Level:** Beginner–Intermediate

---

## Blade Basics

Blade compiles to plain PHP. Files live in `resources/views/`.

```blade
{{-- Output escaped HTML --}}
<h1>{{ $task->title }}</h1>

{{-- Unescaped (use carefully) --}}
{!! $htmlContent !!}

{{-- Directives --}}
@if($task->isOverdue())
    <span class="text-red-600">Overdue</span>
@endif

@foreach($tasks as $task)
    <li>{{ $task->title }}</li>
@endforeach

@forelse($tasks as $task)
    <li>{{ $task->title }}</li>
@empty
    <p>No tasks.</p>
@endforelse
```

---

## Layouts & Sections

**`resources/views/layouts/app.blade.php`** — master layout:

```blade
<title>@yield('title') — {{ config('app.name') }}</title>
@yield('content')
```

**Child view:**

```blade
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
@endsection
```

---

## Components & Partials

**Partial include** — `resources/views/tasks/_form.blade.php`:

```blade
@include('tasks._form', ['action' => route('tasks.store'), 'method' => 'POST'])
```

**Blade components** (Laravel 7+):

```bash
php artisan make:component TaskCard
```

```blade
<x-task-card :task="$task" />
```

---

## Forms & CSRF

Every POST form needs CSRF protection:

```blade
<form method="POST" action="{{ route('tasks.store') }}">
    @csrf
    @method('PUT')  {{-- For updates --}}
    ...
</form>
```

---

## Validation Errors in Views

```blade
@if($errors->any())
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<input value="{{ old('title', $task->title ?? '') }}">
```

---

## TaskForge Views to Study

| File | Concepts |
|------|----------|
| `layouts/app.blade.php` | Layout, nav, flash messages |
| `dashboard.blade.php` | Stats cards, loops, conditionals |
| `tasks/_form.blade.php` | Shared partial, old() helper |
| `tasks/show.blade.php` | Nested data, multiple forms |
| `tasks/index.blade.php` | Pagination, filters |

---

## Pagination

Controller:

```php
$tasks = Task::paginate(15);
```

View:

```blade
{{ $tasks->links() }}
```

---

## Exercises

1. Add a `@section('sidebar')` to the layout for project filters.
2. Create a Blade component `<x-status-badge :status="$task->status" />`.
3. Add flash message display for `session('error')`.

---

## Next Module

→ [Module 05: Database & Eloquent](05-database-eloquent.md)
