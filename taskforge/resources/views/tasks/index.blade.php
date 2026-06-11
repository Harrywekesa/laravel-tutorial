@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">New Task</a>
</div>

<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
           class="border rounded-lg px-3 py-2 flex-1 max-w-xs">
    <select name="status" class="border rounded-lg px-3 py-2">
        <option value="">All statuses</option>
        @foreach(\App\Enums\TaskStatus::cases() as $status)
            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-gray-200 px-4 py-2 rounded-lg">Filter</button>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 text-left text-sm text-gray-500">
            <tr>
                <th class="p-4">Title</th>
                <th class="p-4">Project</th>
                <th class="p-4">Status</th>
                <th class="p-4">Priority</th>
                <th class="p-4">Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4"><a href="{{ route('tasks.show', $task) }}" class="font-medium hover:text-blue-600">{{ $task->title }}</a></td>
                    <td class="p-4 text-gray-500">{{ $task->project->name }}</td>
                    <td class="p-4"><span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $task->status->label() }}</span></td>
                    <td class="p-4">{{ $task->priority->label() }}</td>
                    <td class="p-4 {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">{{ $task->due_at?->format('M j, Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-8 text-center text-gray-500">No tasks found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $tasks->links() }}
@endsection
