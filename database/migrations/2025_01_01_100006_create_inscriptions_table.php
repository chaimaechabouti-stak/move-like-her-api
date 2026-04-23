<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Souscription d'un membre à un abonnement
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('abonnement_id')->constrained('abonnements')->cascadeOnDelete();
            $table->foreignId('salle_id')->nullable()->constrained('salles')->nullOnDelete();
            $table->enum('frequence', ['mensuel', 'annuel'])->default('mensuel');
            $table->unsignedInteger('montant_paye');
            $table->enum('statut', ['active', 'expiree', 'annulee', 'en_attente'])->default('en_attente');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
