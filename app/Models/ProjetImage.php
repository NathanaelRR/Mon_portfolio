<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'projet_id',
        'path',
        'legend',
        'ordre',
    ];

    // 🔹 Relation vers le projet
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}
