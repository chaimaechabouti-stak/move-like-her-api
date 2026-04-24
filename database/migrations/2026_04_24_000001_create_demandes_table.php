<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('name');
            $table->string('email');
            $table->string('telephone', 20)->nullable();
            $table->string('ville')->nullable();
            $table->string('formule')->nullable();
            $table->enum('statut', ['nouveau', 'contacte', 'inscrit', 'annule'])->default('nouveau');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
