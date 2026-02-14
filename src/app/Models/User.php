<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Os projetos que o usuário é dono
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    //Os projetos que o usuário é membro, mas não necessariamente dono
    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps();
    }

    //As tarefas que o usuário é responsável
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //Todos os projetos relacionados ao usuário, seja como dono ou membro
    public function allProjects()
    {
        return Project::where('owner_id', $this->id)
            ->orWhereHas('members', function ($query) {
                $query->where('users.id', $this->id);
            });
    }
}
