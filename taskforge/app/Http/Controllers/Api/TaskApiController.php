<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = Task::forUser($request->user())
            ->with(['project', 'assignees'])
            ->latest()
            ->paginate(20);

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = Task::create([
            ...$request->safe()->except('assignee_ids'),
            'user_id' => $request->user()->id,
            'status' => $request->input('status', 'pending'),
            'priority' => $request->input('priority', 'medium'),
        ]);

        if ($request->assignee_ids) {
            $task->assignees()->sync($request->assignee_ids);
        }

        $task->load(['project', 'assignees']);

        return new TaskResource($task);
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        $task->load(['project', 'assignees', 'comments.author']);

        return new TaskResource($task);
    }

    public function update(StoreTaskRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $task->update($request->safe()->except('assignee_ids'));

        if ($request->has('assignee_ids')) {
            $task->assignees()->sync($request->assignee_ids ?? []);
        }

        $task->load(['project', 'assignees']);

        return new TaskResource($task);
    }

    public function destroy(Task $task): Response
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->noContent();
    }
}
