<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exposants', function (Blueprint $table) {
            $table->id();

            $table->string('nom_entreprise');
            $table->string('responsable');
            $table->string('telephone');
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();

            // Statut (en_attente, validé, refusé)
            $table->string('statut')->default('en_attente');

            // Secteur activité
            $table->string('secteur_activite')->nullable();

            // Site web
            $table->string('site_web')->nullable();

            // Réseaux sociaux
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();

            // Infos entreprise
            $table->string('numero_registre')->nullable();

            // Stand exposant
            $table->string('stand_numero')->nullable();

            // Date inscription
            $table->date('date_inscription')->nullable();

            // Premium / VIP
            $table->boolean('is_premium')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exposants');
    }
};