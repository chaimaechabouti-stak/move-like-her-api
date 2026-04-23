<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->foreignId('ville_id')->constrained('villes')->cascadeOnDelete();
            $table->string('adresse');
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('horaires')->default('6h - 22h');
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
