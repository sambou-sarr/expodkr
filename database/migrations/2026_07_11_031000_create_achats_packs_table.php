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
    Schema::create('achats_packs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('organisateur_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('evenement_id')->constrained('evenements')->cascadeOnDelete();
        $table->foreignId('pack_id')->constrained('packs')->cascadeOnDelete();

        $table->decimal('montant', 10, 2)->default(0);
        $table->string('mode_paiement'); // wave | orange | virement | gratuit
        $table->string('reference')->unique();
        $table->string('statut')->default('en_attente'); // en_attente | confirme | echoue

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('achats_packs');
}
};
