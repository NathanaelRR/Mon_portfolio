<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Projet;

class ProjetImageFactory extends Factory
{
    protected $model = \App\Models\ProjetImage::class;

    public function definition()
    {
        return [
            'projet_id' => Projet::factory(),
            'path' => 'projets/' . $this->faker->image('storage/app/public/projets', 640, 480, null, false),
            'legend' => $this->faker->sentence(5),
            'ordre' => $this->faker->numberBetween(0,3),
        ];
    }
}
