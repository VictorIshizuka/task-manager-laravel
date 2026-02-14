<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $owner = User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => bcrypt('password'),
        ]);

        $member = User::create([
            'name' => 'Maria Souza',
            'email' => 'maria@example.com',
            'password' => bcrypt('password'),
        ]);

        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Sistema de Gestão de Tarefas',
            'description' => 'Projeto para controle interno de tarefas da equipe.',
            'start_date' => now(),
            'due_date' => now()->addDays(45),
        ]);

        $project->members()->attach([$owner->id, $member->id]);

        Task::create([
            'project_id' => $project->id,
            'user_id' => $owner->id,
            'title' => 'Definir arquitetura',
            'description' => 'Definir estrutura MVC e modelagem do banco.',
            'due_date' => now()->addDays(7),
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        Task::create([
            'project_id' => $project->id,
            'user_id' => $member->id,
            'title' => 'Criar migrations',
            'description' => 'Criar migrations iniciais do projeto.',
            'due_date' => now()->addDays(10),
            'priority' => 'medium',
            'status' => 'pending',
        ]);
    }
}
