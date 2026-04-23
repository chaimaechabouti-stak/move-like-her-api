<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('duree')->default('45 min');
            $table->enum('niveau', ['Tous niveaux', 'Débutant', 'Intermédiaire', 'Avancé'])->default('Tous niveaux');
            $table->string('calories')->nullable();
            $table->string('image_url')->nullable();
            $table->string('couleur', 20)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
