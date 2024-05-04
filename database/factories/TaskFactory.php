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
        $start_date = fake()->dateTimeBetween('+0 days', '+1 month');
        $random = rand(1,3);
        return [
            'title' => fake()->sentence(20),
            'description' => fake()->sentence(100),
            'priority' => $random,
            'due_date' => $start_date,
        ];
    }
}
