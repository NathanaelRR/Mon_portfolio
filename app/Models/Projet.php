<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'resume',
        'description_conception',
        'description_maquettes',
        'description_developpement',
        'description_difficultes',
        'technologies',
        'apport_personnel',
    ];

    // 🔹 Relation vers toutes les étapes
    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }

    // 🔹 Étapes conception uniquement, triées par ordre
    public function etapesConception()
    {
        return $this->hasMany(Etape::class)
                    ->where('categorie', 'conception')
                    ->orderBy('ordre');
    }

    // 🔹 Étapes développement uniquement, triées par ordre
    public function etapesDeveloppement()
    {
        return $this->hasMany(Etape::class)
                    ->where('categorie', 'developpement')
                    ->orderBy('ordre');
    }

    // 🔹 Relation vers les images multiples
    public function images()
    {
        return $this->hasMany(ProjetImage::class)->orderBy('ordre');
    }

    // 🔹 Cast JSON pour technologies
    protected $casts = [
        'technologies' => 'array',
    ];
}
