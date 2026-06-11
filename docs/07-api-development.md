# Module 07: REST API Development

**Duration:** 5–7 days | **Level:** Intermediate

---

## API Design Principles

| Method | URI | Action |
|--------|-----|--------|
| GET | /api/tasks | List tasks |
| POST | /api/tasks | Create task |
| GET | /api/tasks/{id} | Show task |
| PUT/PATCH | /api/tasks/{id} | Update task |
| DELETE | /api/tasks/{id} | Delete task |

Use proper HTTP status codes: `200`, `201`, `204`, `401`, `403`, `404`, `422`.

---

## API Resources

Transform models to consistent JSON:

```php
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status->value,
            'project' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
```

**Controller:**

```php
return TaskResource::collection($tasks);  // Paginated list
return new TaskResource($task);           // Single item
```

---

## Sanctum Token Auth

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**Generate token:**

```php
$token = $user->createToken('mobile-app')->plainTextToken;
// Returns: "1|abc123..."
```

**API request:**

```bash
curl -H "Authorization: Bearer 1|abc123..." \
     -H "Accept: application/json" \
     http://localhost:8000/api/tasks
```

---

## API Versioning

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::apiResource('tasks', TaskApiController::class);
});
```

---

## Rate Limiting

```php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(...);
```

---

## TaskForge API Files

- `routes/api.php`
- `app/Http/Controllers/Api/TaskApiController.php`
- `app/Http/Resources/TaskResource.php`
- `tests/Feature/Api/TaskApiTest.php`

---

## Exercises

1. Add `POST /api/tasks/{task}/complete` endpoint.
2. Create `CommentResource` and nest comments in task response.
3. Write API test for 403 when accessing another user's task.

---

## Next Module

→ [Module 08: Frontend Assets](08-frontend-assets.md)
