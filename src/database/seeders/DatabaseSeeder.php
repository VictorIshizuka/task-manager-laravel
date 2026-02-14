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

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' =>  Hash::make('password'),
        ]);

        $project = Project::factory()->create([
            'owner_id' => $user->id
        ]);

        $project->members()->attach($user->id);

        Task::factory()->count(5)->create([
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
    }
}
