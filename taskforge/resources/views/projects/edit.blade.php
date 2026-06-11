@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Project</h1>
<form method="POST" action="{{ route('projects.update', $project) }}" class="bg-white p-6 rounded-lg shadow max-w-lg space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $project->name) }}" required class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description', $project->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Color</label>
        <input type="color" name="color" value="{{ old('color', $project->color) }}" class="h-10 w-20">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Save</button>
        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline">Delete</button>
        </form>
    </div>
</form>
@endsection
