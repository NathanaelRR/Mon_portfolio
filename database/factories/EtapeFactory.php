<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Projet;

class EtapeFactory extends Factory
{
    protected $model = \App\Models\Etape::class;

    public function definition()
    {
        return [
            'projet_id' => Projet::factory(),
            'categorie' => $this->faker->randomElement(['conception', 'developpement']),
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'ordre' => $this->faker->numberBetween(0,5),
        ];
    }
}
