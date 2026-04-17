<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4, false),
            'description' => fake()->paragraph(),
        ];
    }
}
