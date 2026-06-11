<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Events\TaskCompleted;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\ActivityLog;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function index(Request $request): View
    {
        $tasks = Task::forUser($request->user())
            ->with(['project', 'assignees'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);

        $projects = Project::where('user_id', $request->user()->id)->active()->get();
        $users = User::orderBy('name')->get();

        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $task = Task::create([
            ...$request->safe()->except('assignee_ids'),
            'user_id' => $request->user()->id,
        ]);

        if ($request->assignee_ids) {
            $task->assignees()->sync($request->assignee_ids);
        }

        ActivityLog::record('task.created', $task, $request->user());
        $this->dashboardService->clearCache($request->user());

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load(['project', 'creator', 'assignees', 'comments.author', 'attachments.uploader']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $projects = Project::where('user_id', auth()->id())->active()->get();
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(StoreTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $wasCompleted = $task->status === TaskStatus::Completed;

        $task->update($request->safe()->except('assignee_ids'));

        if ($request->has('assignee_ids')) {
            $task->assignees()->sync($request->assignee_ids ?? []);
        }

        if (! $wasCompleted && $task->status === TaskStatus::Completed) {
            $task->update(['completed_at' => now()]);
            event(new TaskCompleted($task, $request->user()));
        }

        $this->dashboardService->clearCache($request->user());

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();
        $this->dashboardService->clearCache(auth()->user());

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted.');
    }

    public function complete(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->markComplete();
        event(new TaskCompleted($task, auth()->user()));
        $this->dashboardService->clearCache(auth()->user());

        return back()->with('success', 'Task marked as complete.');
    }

    public function storeComment(StoreCommentRequest $request, Task $task): RedirectResponse
    {
        $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function storeAttachment(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'file' => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        Attachment::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded.');
    }
}
