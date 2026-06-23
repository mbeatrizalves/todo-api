<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo'       => $this->faker->sentence(4),
            'descricao'    => $this->faker->optional()->paragraph(),
            'status'       => $this->faker->randomElement(['pendente', 'em_andamento', 'concluida']),
            'prazo'        => $this->faker->optional()->dateTimeBetween('now', '+3 months')?->format('Y-m-d'),
            'categoria_id' => Categoria::factory(),
            'user_id'      => User::factory(),
        ];
    }
}
