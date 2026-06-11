@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    @foreach(['total' => 'Total', 'pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'overdue' => 'Overdue'] as $key => $label)
    <div class="bg-white p-4 rounded-lg shadow border-l-4 {{ $key === 'overdue' && $stats[$key] > 0 ? 'border-red-500' : 'border-blue-500' }}">
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="text-2xl font-bold">{{ $stats[$key] }}</p>
    </div>
    @endforeach
</div>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Recent Tasks</h2>
        @forelse($recentTasks as $task)
            <a href="{{ route('tasks.show', $task) }}" class="block py-2 border-b last:border-0 hover:bg-gray-50 -mx-2 px-2 rounded">
                <span class="font-medium">{{ $task->title }}</span>
                <span class="text-sm text-gray-500"> — {{ $task->project->name }}</span>
            </a>
        @empty
            <p class="text-gray-500">No tasks yet.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 text-red-600">Overdue Tasks</h2>
        @forelse($overdueTasks as $task)
            <a href="{{ route('tasks.show', $task) }}" class="block py-2 border-b last:border-0 hover:bg-gray-50 -mx-2 px-2 rounded">
                <span class="font-medium">{{ $task->title }}</span>
                <span class="text-sm text-red-500">Due {{ $task->due_at->diffForHumans() }}</span>
            </a>
        @empty
            <p class="text-gray-500">All caught up!</p>
        @endforelse
    </div>
</div>

<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
    @forelse($activity as $log)
        <div class="py-2 border-b last:border-0 text-sm">
            <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
            <span class="text-gray-500">{{ str_replace('.', ' ', $log->action) }}</span>
            <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
        </div>
    @empty
        <p class="text-gray-500">No activity yet.</p>
    @endforelse
</div>
@endsection
