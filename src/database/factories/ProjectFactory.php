<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => 1,
            'title' => 'Sistema Interno',
            'description' => 'Projeto para desenvolvimento do sistema interno da empresa.',
            'start_date' => now(),
            'due_date' => now()->addDays(30),
        ];
    }
}
