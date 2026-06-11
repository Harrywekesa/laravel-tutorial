<form method="POST" action="{{ $action }}" class="bg-white p-6 rounded-lg shadow max-w-2xl space-y-4">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div>
        <label class="block text-sm font-medium mb-1">Project</label>
        <select name="project_id" required class="w-full border rounded-lg px-3 py-2">
            @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id', $task->project_id ?? request('project_id')) == $project->id)>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2">
                @foreach(\App\Enums\TaskStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(old('status', isset($task) ? $task->status->value : 'pending') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Priority</label>
            <select name="priority" class="w-full border rounded-lg px-3 py-2">
                @foreach(\App\Enums\TaskPriority::cases() as $priority)
                    <option value="{{ $priority->value }}" @selected(old('priority', isset($task) ? $task->priority->value : 'medium') === $priority->value)>{{ $priority->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Due Date</label>
        <input type="date" name="due_at" value="{{ old('due_at', isset($task) ? $task->due_at?->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Assignees</label>
        <select name="assignee_ids[]" multiple class="w-full border rounded-lg px-3 py-2 h-32">
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(collect(old('assignee_ids', isset($task) ? $task->assignees->pluck('id') : []))->contains($user->id))>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Save Task</button>
</form>
