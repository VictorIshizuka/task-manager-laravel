<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'start_date',
        'due_date'
    ];

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
        if ($this->tasks()->where('status', '!=', 'completed')->exists()) {
            return 'in_progress';
        }

        return 'completed';
    }

    // Progresso do projeto baseado nas tarefas
    public function getProgressAttribute()
    {
        $total = $this->tasks()->count();

        if ($total === 0) return 0;

        $completed = $this->tasks()
            ->where('status', 'completed')
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
