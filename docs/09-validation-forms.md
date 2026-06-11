# Module 09: Validation & Form Requests

**Duration:** 3–4 days | **Level:** Intermediate

---

## Inline Validation

```php
$validated = $request->validate([
    'title' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users'],
    'due_at' => ['nullable', 'date', 'after_or_equal:today'],
]);
```

---

## Form Request Classes

```bash
php artisan make:request StoreTaskRequest
```

```php
class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['nullable', Rule::enum(TaskPriority::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a task title.',
        ];
    }
}
```

**Controller:**

```php
public function store(StoreTaskRequest $request): RedirectResponse
{
    $task = Task::create($request->validated());
}
```

---

## Common Validation Rules

| Rule | Description |
|------|-------------|
| `required` | Must be present |
| `nullable` | Can be null |
| `email` | Valid email |
| `unique:users,email` | Unique in table |
| `exists:projects,id` | Foreign key exists |
| `confirmed` | Matches `password_confirmation` |
| `max:255` | Max length |
| `mimes:jpg,png,pdf` | File types |
| `Rule::enum(Status::class)` | PHP Enum value |

---

## Custom Rules

```bash
php artisan make:rule ValidProjectOwner
```

---

## TaskForge Examples

- `StoreProjectRequest.php`
- `StoreTaskRequest.php`
- `StoreCommentRequest.php`

---

## Exercises

1. Add validation: task due date cannot be more than 1 year ahead.
2. Create `UpdateTaskRequest` with `sometimes` rules.
3. Add custom error messages for all TaskForge forms.

---

## Next Module

→ [Module 10: File Storage](10-file-storage.md)
