<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->unsignedInteger('prix_mensuel');
            $table->unsignedInteger('prix_annuel')->nullable();
            $table->json('fonctionnalites'); // liste des features
            $table->boolean('populaire')->default(false);
            $table->string('couleur', 20)->nullable();
            $table->string('cta_texte')->default('Commencer');
            $table->boolean('actif')->default(true);
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
