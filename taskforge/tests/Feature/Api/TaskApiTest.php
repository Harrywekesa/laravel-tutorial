<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_tasks_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Task::factory()->count(3)->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_api_requires_authentication(): void
    {
        $this->getJson('/api/tasks')->assertUnauthorized();
    }

    public function test_api_can_create_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/tasks', [
            'project_id' => $project->id,
            'title' => 'API Task',
            'priority' => 'high',
        ])->assertCreated()
            ->assertJsonPath('data.title', 'API Task');
    }
}
