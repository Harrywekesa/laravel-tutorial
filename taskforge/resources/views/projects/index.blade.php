@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Projects</h1>
    <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">New Project</a>
</div>

<div class="grid md:grid-cols-3 gap-4">
    @forelse($projects as $project)
        <a href="{{ route('projects.show', $project) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-t-4" style="border-color: {{ $project->color }}">
            <h3 class="font-semibold text-lg">{{ $project->name }}</h3>
            <p class="text-gray-500 text-sm mt-1">{{ Str::limit($project->description, 80) }}</p>
            <p class="text-sm text-gray-400 mt-3">{{ $project->tasks_count }} tasks</p>
        </a>
    @empty
        <p class="text-gray-500 col-span-3">No projects yet. Create your first one!</p>
    @endforelse
</div>

{{ $projects->links() }}
@endsection
