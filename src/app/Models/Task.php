<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        /**
         * SOFT DELETE (padrão)
         * Quando deletar com soft delete, não deleta arquivos físicos
         */
        static::deleting(function ($task) {
            if ($task->isForceDeleting()) {
                // FORCE DELETE - Deletar arquivos físicos e registros
                static::forceDeleteTask($task);
            } else {
                // Soft delete - apenas marca como deletado
                Log::info("Task {$task->id} soft deleted (arquivos mantidos para restauração)");
            }
        });

        /**
         * RESTAURAÇÃO
         * Quando restaurar task, os arquivos ainda estao no storage
         */
        static::restoring(function ($task) {
            Log::info("Task {$task->id} restaurada");
        });
    }

    /**
     * Deleta permanentemente a task e todos os seus arquivos
     */
    protected static function forceDeleteTask($task)
    {
        try {
            // 1. Deletar arquivos físicos e registros
            foreach ($task->files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
                $file->delete();
            }

            // 2. Deletar pasta da task (se vazia ou recursivamente)
            $taskPath = 'tasks/' . $task->id;
            if (Storage::disk('public')->exists($taskPath)) {
                Storage::disk('public')->deleteDirectory($taskPath);
            }

            Log::info("Task {$task->id} force deleted com todos os arquivos", [
                'task_id' => $task->id,
                'project_id' => $task->project_id,
                'files_count' => $task->files()->count(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Erro ao force delete da task {$task->id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }
}
