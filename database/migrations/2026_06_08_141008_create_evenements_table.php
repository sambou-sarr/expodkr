<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {

            $table->id();

            $table->string('titre');
            
            $table->foreignId('id_categorie')->constrained('categorie_stands')->onDelete('cascade');
            $table->foreignId('exposant_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description');

            $table->string('lieu');

            $table->date('date_debut');

            $table->date('date_fin');

            $table->string('image')->nullable();

            $table->enum('statut',[
                'brouillon',
                'ouvert',
                'termine'
            ])->default('brouillon');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
