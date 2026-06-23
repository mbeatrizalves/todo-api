<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'      => $this->faker->unique()->word(),
            'descricao' => $this->faker->optional()->sentence(),
            'user_id'   => User::factory(),
        ];
    }
}
