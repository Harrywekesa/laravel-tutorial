@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-bold" style="color: {{ $project->color }}">{{ $project->name }}</h1>
        <p class="text-gray-500 mt-1">{{ $project->description }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:underline">Edit</a>
        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Add Task</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    @forelse($project->tasks as $task)
        <a href="{{ route('tasks.show', $task) }}" class="flex justify-between items-center p-4 border-b last:border-0 hover:bg-gray-50">
            <div>
                <span class="font-medium">{{ $task->title }}</span>
                <span class="ml-2 text-xs px-2 py-1 rounded bg-{{ $task->status->color() }}-100 text-{{ $task->status->color() }}-800">
                    {{ $task->status->label() }}
                </span>
            </div>
            <span class="text-sm text-gray-500">{{ $task->due_at?->format('M j') ?? 'No due date' }}</span>
        </a>
    @empty
        <p class="p-6 text-gray-500">No tasks in this project.</p>
    @endforelse
</div>
@endsection
