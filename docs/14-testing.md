# Module 14: Testing (Unit, Feature, Browser)

**Duration:** 7–10 days | **Level:** Intermediate–Advanced

---

## PHPUnit Setup

Laravel includes PHPUnit. Run tests:

```bash
php artisan test
php artisan test --filter=TaskTest
php artisan test --coverage
```

---

## Feature Tests

Test HTTP endpoints and full flows:

```php
class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'project_id' => $project->id,
                'title' => 'New task',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tasks', ['title' => 'New task']);
    }
}
```

---

## API Tests

```php
Sanctum::actingAs($user);

$this->getJson('/api/tasks')
    ->assertOk()
    ->assertJsonCount(3, 'data');
```

---

## Unit Tests

Test isolated classes without HTTP:

```php
class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_returns_correct_counts(): void
    {
        $service = app(DashboardService::class);
        // ...
    }
}
```

---

## Factories in Tests

```php
$user = User::factory()->create();
Task::factory()->count(5)->overdue()->create([
    'user_id' => $user->id,
]);
```

---

## Mocking

```php
Mail::fake();
Notification::fake();
Queue::fake();
Event::fake();

// Assert
Mail::assertSent(WelcomeMail::class);
Queue::assertPushed(SendTaskDueReminder::class);
```

---

## Browser Tests (Laravel Dusk)

```bash
composer require laravel/dusk --dev
php artisan dusk:install
```

```php
$this->browse(function (Browser $browser) {
    $browser->visit('/login')
        ->type('email', 'admin@taskforge.test')
        ->type('password', 'password')
        ->press('Sign In')
        ->assertPathIs('/dashboard');
});
```

---

## TaskForge Test Suite

| File | Tests |
|------|-------|
| `tests/Feature/AuthTest.php` | Login, register, logout |
| `tests/Feature/TaskTest.php` | CRUD, complete, scopes |
| `tests/Feature/Api/TaskApiTest.php` | Sanctum API |

---

## Testing Best Practices

1. Use `RefreshDatabase` for isolation
2. One assertion concept per test
3. Descriptive test names: `test_guest_cannot_view_tasks`
4. Test happy path AND edge cases
5. Run tests in CI on every push

---

## Exercises

1. Add test: member cannot delete admin's project.
2. Add test: completing task fires `TaskCompleted` event.
3. Achieve 80%+ coverage on controllers.

---

## Next Module

→ [Module 15: Security](15-security.md)
