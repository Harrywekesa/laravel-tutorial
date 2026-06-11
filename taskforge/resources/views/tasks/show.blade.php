@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="flex justify-between items-start mb-6">
    <div>
        <p class="text-sm text-gray-500"><a href="{{ route('projects.show', $task->project) }}" class="hover:text-blue-600">{{ $task->project->name }}</a></p>
        <h1 class="text-2xl font-bold mt-1">{{ $task->title }}</h1>
        <div class="flex gap-2 mt-2">
            <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $task->status->label() }}</span>
            <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $task->priority->label() }}</span>
            @if($task->due_at)
                <span class="text-xs px-2 py-1 rounded {{ $task->isOverdue() ? 'bg-red-100 text-red-800' : 'bg-gray-100' }}">
                    Due {{ $task->due_at->format('M j, Y') }}
                </span>
            @endif
        </div>
    </div>
    <div class="flex gap-2">
        @if($task->status !== \App\Enums\TaskStatus::Completed)
            <form method="POST" action="{{ route('tasks.complete', $task) }}">
                @csrf @method('PATCH')
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Complete</button>
            </form>
        @endif
        <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:underline py-2">Edit</a>
    </div>
</div>

@if($task->description)
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
    </div>
@endif

@if($task->assignees->isNotEmpty())
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-500 mb-2">Assignees</h3>
        <div class="flex gap-2">
            @foreach($task->assignees as $assignee)
                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm">{{ $assignee->name }}</span>
            @endforeach
        </div>
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4">Comments</h2>
        @forelse($task->comments as $comment)
            <div class="mb-4 pb-4 border-b last:border-0">
                <p class="text-sm font-medium">{{ $comment->author->name }} <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span></p>
                <p class="mt-1">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No comments yet.</p>
        @endforelse

        <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="mt-4">
            @csrf
            <textarea name="body" rows="2" placeholder="Add a comment..." required class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            <button type="submit" class="mt-2 bg-blue-600 text-white px-3 py-1 rounded text-sm">Post</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4">Attachments</h2>
        @forelse($task->attachments as $attachment)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <a href="{{ $attachment->url() }}" target="_blank" class="text-blue-600 hover:underline text-sm">{{ $attachment->original_name }}</a>
                <span class="text-xs text-gray-400">{{ number_format($attachment->size / 1024, 1) }} KB</span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No attachments.</p>
        @endforelse

        <form method="POST" action="{{ route('tasks.attachments.store', $task) }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input type="file" name="file" required class="text-sm">
            <button type="submit" class="mt-2 bg-gray-200 px-3 py-1 rounded text-sm">Upload</button>
        </form>
    </div>
</div>
@endsection
