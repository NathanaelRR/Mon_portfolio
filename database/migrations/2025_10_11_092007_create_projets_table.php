<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('type')->default('Personnel');
            $table->json('technologies')->nullable();
            $table->text('resume')->nullable();

            $table->text('description_conception')->nullable();
            $table->text('description_maquettes')->nullable();
            $table->text('description_developpement')->nullable();
            $table->text('description_difficultes')->nullable();

            $table->text('apport_personnel')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
