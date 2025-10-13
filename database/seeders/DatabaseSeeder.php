<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projet;
use App\Models\Etape;
use App\Models\ProjetImage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 5 projets avec étapes et images
        Projet::factory(5)->create()->each(function ($projet) {
            // Ajouter 2-4 étapes par projet
            Etape::factory(rand(2,4))->create(['projet_id' => $projet->id]);

            // Ajouter 1-3 images par projet
            ProjetImage::factory(rand(1,3))->create(['projet_id' => $projet->id]);
        });
    }
}
