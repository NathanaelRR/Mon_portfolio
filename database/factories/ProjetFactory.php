<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetFactory extends Factory
{
    protected $model = \App\Models\Projet::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['Académique', 'Pro', 'Perso']),
            'resume' => $this->faker->paragraph(2),
            'description_conception' => $this->faker->paragraph(),
            'description_maquettes' => $this->faker->paragraph(),
            'description_developpement' => $this->faker->paragraph(),
            'description_difficultes' => $this->faker->paragraph(),
            'apport_personnel' => $this->faker->sentence(),
            'technologies' => $this->faker->randomElements(['Laravel','Vue','React','MySQL','SQLite'], 3),
        ];
    }
}
