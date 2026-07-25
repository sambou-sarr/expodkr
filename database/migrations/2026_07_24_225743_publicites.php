<?php
// database/migrations/2025_01_01_000010_create_publicites_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicites', function (Blueprint $table) {
            $table->id();

            // Identification
            $table->string('zone');          // ap_habillage_gauche, top_a2m, splh, a1r, bloc_special, b1l, b1r...
            $table->string('titre');         // Nom interne (ex : "Campagne CCIAD Juin 2026")
            $table->string('client')->nullable();  // Nom de l'annonceur

            // Contenu
            $table->string('image');         // URL Cloudinary complète
            $table->string('url');           // Lien de clic (URL externe)
            $table->string('alt')->nullable(); // Texte alt accessibilité

            // Planification
            $table->boolean('actif')->default(true);
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();

            // Priorité (si plusieurs actives sur la même zone)
            $table->integer('priorite')->default(0);

            // Stats
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clics')->default(0);

            $table->timestamps();

            // Index
            $table->index(['zone', 'actif']);
            $table->index(['date_debut', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicites');
    }
};