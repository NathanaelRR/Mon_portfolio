<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->string('categorie'); // 'conception' ou 'developpement'
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0); // pour trier les étapes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes');
    }
};
