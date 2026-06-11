<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@taskforge.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);

        $member = User::create([
            'name' => 'Team Member',
            'email' => 'member@taskforge.test',
            'password' => Hash::make('password'),
            'role' => UserRole::Member,
        ]);

        $website = Project::create([
            'user_id' => $admin->id,
            'name' => 'Website Redesign',
            'description' => 'Complete overhaul of the company website with modern UI.',
            'color' => '#3b82f6',
        ]);

        $mobile = Project::create([
            'user_id' => $admin->id,
            'name' => 'Mobile App',
            'description' => 'Native mobile application for iOS and Android.',
            'color' => '#10b981',
        ]);

        $tasks = [
            ['project' => $website, 'title' => 'Design homepage mockup', 'status' => TaskStatus::Completed, 'priority' => TaskPriority::High],
            ['project' => $website, 'title' => 'Implement responsive navigation', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium],
            ['project' => $website, 'title' => 'Write content for About page', 'status' => TaskStatus::Pending, 'priority' => TaskPriority::Low, 'overdue' => true],
            ['project' => $mobile, 'title' => 'Set up React Native project', 'status' => TaskStatus::Completed, 'priority' => TaskPriority::High],
            ['project' => $mobile, 'title' => 'Build authentication flow', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Urgent],
            ['project' => $mobile, 'title' => 'API integration testing', 'status' => TaskStatus::Pending, 'priority' => TaskPriority::Medium],
        ];

        foreach ($tasks as $data) {
            $task = Task::create([
                'project_id' => $data['project']->id,
                'user_id' => $admin->id,
                'title' => $data['title'],
                'description' => 'Sample task for learning Laravel with TaskForge.',
                'status' => $data['status'],
                'priority' => $data['priority'],
                'due_at' => isset($data['overdue']) ? now()->subDays(3) : now()->addDays(rand(3, 14)),
                'completed_at' => $data['status'] === TaskStatus::Completed ? now()->subDay() : null,
            ]);

            $task->assignees()->attach($member->id);

            Comment::create([
                'task_id' => $task->id,
                'user_id' => $member->id,
                'body' => 'Looking good! Let me know if you need help.',
            ]);
        }
    }
}
