<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projet_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained()->onDelete('cascade');
            $table->string('path'); // chemin du fichier image
            $table->string('legend')->nullable(); // légende optionnelle
            $table->integer('ordre')->default(0); // tri des images
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projet_images');
    }
};
