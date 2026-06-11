<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'size',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->filename);
    }

    protected static function booted(): void
    {
        static::deleting(function (Attachment $attachment) {
            Storage::disk('public')->delete($attachment->filename);
        });
    }
}
