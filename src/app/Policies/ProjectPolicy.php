<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Pode ver a lista de projetos, desde que seja membro ou dono de algum projeto
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     *  Pode ver o projeto se for dono ou membro
     */
    public function view(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id
            || $project->members()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Apenas o dono do projeto pode editar
     */
    public function update(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    /**
     * Apenas o dono do projeto pode deletar
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Project $project): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Project $project): bool
    // {
    //     return false;
    // }

    // Apenas o dono do projeto pode adicionar membros
    public function addMember(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    // Apenas o dono do projeto pode remover membros
    public function removeMember(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    //
}
