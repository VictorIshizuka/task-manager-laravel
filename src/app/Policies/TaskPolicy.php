<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Apenas membros de algum projeto podem ver a lista de tarefas
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Pode ver a tarefa se for membro do projeto ou dono da tarefa
     */
    public function view(User $user, Task $task): bool
    {
        return $task->user_id === $user->id || ($task->project->owner_id === $user->id
            || $task->project->members->contains($user->id));
    }

    /**
     * Pode criar tarefa se for membro do projeto ou dono do projeto
     */
    public function create(User $user,  Project $project): bool
    {
        return $project->owner_id === $user->id
            || $project->members->contains($user->id);
    }

    /**
     * Pode atualizar a tarefa se for membro do projeto ou dono da tarefa
     */
    public function update(User $user, Task $task): bool
    {
        return $task->project->members->contains($user->id)
            || $task->project->owner_id === $user->id;
    }

    /**
     * Pode deletar a tarefa se for dono da tarefa
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
