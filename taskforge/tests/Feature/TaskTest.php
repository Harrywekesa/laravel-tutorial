<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => UserRole::Admin]);
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_guest_cannot_view_tasks(): void
    {
        $this->get(route('tasks.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_create_task(): void
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), [
            'project_id' => $this->project->id,
            'title' => 'New test task',
            'description' => 'Task description',
            'status' => TaskStatus::Pending->value,
            'priority' => 'medium',
            'due_at' => now()->addWeek()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['title' => 'New test task']);
    }

    public function test_user_can_complete_task(): void
    {
        $task = Task::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
            'status' => TaskStatus::InProgress,
        ]);

        $this->actingAs($this->user)
            ->patch(route('tasks.complete', $task))
            ->assertRedirect();

        $task->refresh();
        $this->assertEquals(TaskStatus::Completed, $task->status);
        $this->assertNotNull($task->completed_at);
    }

    public function test_user_can_view_own_task(): void
    {
        $task = Task::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSee($task->title);
    }

    public function test_overdue_scope_finds_past_due_tasks(): void
    {
        Task::factory()->overdue()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ]);

        Task::factory()->completed()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals(1, Task::overdue()->count());
    }
}
