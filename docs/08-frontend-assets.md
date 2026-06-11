# Module 08: Frontend Assets (Vite, Tailwind, Alpine.js)

**Duration:** 3–5 days | **Level:** Intermediate

---

## Vite Integration

Laravel uses Vite for bundling CSS/JS:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Commands

```bash
npm install
npm run dev      # Development with HMR
npm run build    # Production build
```

### File Structure

```
resources/
├── css/app.css       # Tailwind imports
├── js/app.js         # Entry point
└── views/            # Blade templates
```

---

## Tailwind CSS

Laravel 12 ships with Tailwind 4. Style in Blade:

```html
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-semibold text-gray-900">Tasks</h2>
</div>
```

Utility-first approach — no custom CSS files needed for most UI.

---

## Alpine.js (Optional)

Lightweight JS for interactivity:

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>
```

---

## Livewire & Inertia (Advanced UI)

| Tool | Use Case |
|------|----------|
| **Livewire** | Full-stack components without writing API |
| **Inertia.js** | SPA feel with Vue/React + Laravel backend |

```bash
composer require livewire/livewire
# or
composer require inertiajs/inertia-laravel
```

---

## Production Assets

```bash
npm run build
```

Compiled files go to `public/build/`. Never run `npm run dev` in production.

---

## Exercises

1. Run `npm run dev` and add a hover effect to task cards.
2. Add Alpine.js dropdown for task status filter.
3. Build assets and verify `@vite` works without dev server.

---

## Next Module

→ [Module 09: Validation & Form Requests](09-validation-forms.md)
