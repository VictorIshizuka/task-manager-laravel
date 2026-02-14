<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => 1,
            'user_id' => 1,
            'title' => 'Configurar ambiente',
            'description' => 'Configurar ambiente local com Docker.',
            'due_date' => now()->addDays(5),
            'priority' => 'high',
            'status' => 'pending',
        ];
    }
}
