<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;

    protected $fillable = [
        'projet_id',
        'categorie',
        'titre',
        'description',
        'ordre',
    ];

    // 🔹 Relation vers le projet
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}
