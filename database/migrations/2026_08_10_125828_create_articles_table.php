<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->string('slug')->unique();

            $table->string('image')->nullable();

            $table->text('extrait')->nullable();

            $table->longText('contenu');

            $table->string('categorie')->nullable();

            $table->foreignId('auteur_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('date_publication')->nullable();

            $table->unsignedInteger('temps_lecture')->default(5);

            $table->enum('statut', [
                'brouillon',
                'publie'
            ])->default('brouillon');

            $table->timestamps();

            $table->index('categorie');
            $table->index('statut');
            $table->index('date_publication');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};