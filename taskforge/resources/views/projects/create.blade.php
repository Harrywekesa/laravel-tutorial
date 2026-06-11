@extends('layouts.app')

@section('title', 'New Project')

@section('content')
<h1 class="text-2xl font-bold mb-6">Create Project</h1>
<form method="POST" action="{{ route('projects.store') }}" class="bg-white p-6 rounded-lg shadow max-w-lg space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Color</label>
        <input type="color" name="color" value="{{ old('color', '#3b82f6') }}" class="h-10 w-20">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Create Project</button>
</form>
@endsection
