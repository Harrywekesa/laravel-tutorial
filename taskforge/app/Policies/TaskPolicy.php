<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $this->canAccess($user, $task);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $this->canAccess($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id
            || $task->project->user_id === $user->id
            || $user->isAdmin();
    }

    private function canAccess(User $user, Task $task): bool
    {
        return $user->id === $task->user_id
            || $task->project->user_id === $user->id
            || $task->assignees->contains('id', $user->id)
            || $user->isAdmin();
    }
}
