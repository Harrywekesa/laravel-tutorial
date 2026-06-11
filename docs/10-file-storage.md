# Module 10: File Storage & Media

**Duration:** 3–4 days | **Level:** Intermediate

---

## Filesystem Configuration

`config/filesystems.php` defines disks:

| Disk | Driver | Use |
|------|--------|-----|
| `local` | Local filesystem | Private files |
| `public` | Local + symlink | Public uploads |
| `s3` | AWS S3 | Cloud storage |

---

## Uploading Files (TaskForge)

```php
$request->validate([
    'file' => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,png'],
]);

$path = $request->file('file')->store('attachments', 'public');

Attachment::create([
    'filename' => $path,
    'original_name' => $file->getClientOriginalName(),
    'mime_type' => $file->getMimeType(),
    'size' => $file->getSize(),
]);
```

### Public Access

```bash
php artisan storage:link
```

Creates `public/storage` → `storage/app/public` symlink.

**URL:** `Storage::disk('public')->url($path)`

---

## Downloading & Deleting

```php
Storage::disk('public')->download($attachment->filename, $attachment->original_name);
Storage::disk('public')->delete($attachment->filename);
```

TaskForge auto-deletes files in `Attachment::booted()`.

---

## S3 Production Setup

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=my-bucket
```

---

## Exercises

1. Add image thumbnail generation with Intervention Image.
2. Limit total attachments per task to 5.
3. Add download route with authorization check.

---

## Next Module

→ [Module 11: Queues, Jobs & Scheduling](11-queues-jobs.md)
