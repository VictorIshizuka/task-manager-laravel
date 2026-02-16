<?php

namespace App\Models;



use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, SoftDeletes};
use Illuminate\Support\Facades\{Log, Storage};

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'project_id',
        'title',
        'description',
        'start_date',
        'due_date'

    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        /**
         * SOFT DELETE (padrão)
         * Quando deletar com soft delete, também soft deleta as tasks
         */
        static::deleting(function ($project) {
            if (!$project->isForceDeleting()) {
                // Soft delete nas tasks
                $project->tasks()->delete();

                Log::info("Project {$project->id} soft deleted com suas tasks");
            } else {
                // FORCE DELETE - Deletar tudo permanente
                static::forceDeleteProject($project);
            }
        });

        /**
         * RESTAURAÇÃO
         * Quando restaurar projeto, também restaura as tasks
         */
        static::restoring(function ($project) {
            $project->tasks()->withTrashed()->restore();

            Log::info("Project {$project->id} restaurado com suas tasks");
        });
    }
    /**
     * Deleta permanentemente o projeto e todos os seus recursos
     */
    protected static function forceDeleteProject($project)
    {
        try {
            // 1. Deletar arquivos físicos e registros das TASKS
            foreach ($project->tasks()->withTrashed()->get() as $task) {
                foreach ($task->files as $taskFile) {
                    if (Storage::disk('public')->exists($taskFile->path)) {
                        Storage::disk('public')->delete($taskFile->path);
                    }
                    $taskFile->delete();
                }
                $task->forceDelete();
            }

            // 2. Deletar arquivos físicos e registros do PROJETO
            foreach ($project->files as $projectFile) {
                if (Storage::disk('public')->exists($projectFile->path)) {
                    Storage::disk('public')->delete($projectFile->path);
                }
                $projectFile->forceDelete();
            }

            // 3. Remover membros do projeto
            $project->members()->detach();

            // 4. Deletar pasta inteira do projeto (se vazia)
            $projectPath = 'projects/' . $project->id;
            if (Storage::disk('public')->exists($projectPath)) {
                Storage::disk('public')->deleteDirectory($projectPath);
            }

            // 5. Deletar pasta de tasks do projeto
            foreach ($project->tasks()->withTrashed()->pluck('id') as $taskId) {
                $taskPath = 'tasks/' . $taskId;
                if (Storage::disk('public')->exists($taskPath)) {
                    Storage::disk('public')->deleteDirectory($taskPath);
                }
            }

            Log::info("Project {$project->id} force deleted com todos os recursos", [
                'project_id' => $project->id,
                'tasks_count' => $project->tasks()->withTrashed()->count(),
                'files_count' => $project->files()->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Erro ao force delete do projeto {$project->id}: " . $e->getMessage());
            throw $e;
        }
    }


    // Os usuários que são membros do projeto
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    // As tarefas associadas ao projeto
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Status do projeto baseado nas tarefas
    public function getStatusAttribute()
    {
        if ($this->tasks()->where('status', '!=', 'done')->exists()) {
            return 'in_progress';
        }

        return 'active';
    }

    // Progresso do projeto baseado nas tarefas
    public function getProgressAttribute()
    {
        $total = $this->tasks()->count();

        if ($total === 0) return 0;

        $completed = $this->tasks()
            ->where('status', 'done')
            ->count();

        return intval(($completed / $total) * 100);
    }


    // Os arquivos associados ao projeto
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }


    // O usuário que é dono do projeto
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
