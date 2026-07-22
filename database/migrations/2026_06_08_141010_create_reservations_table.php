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
        Schema::create('reservations', function (Blueprint $table) {
             $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exposant_id')->nullable()->constrained('exposants')->nullOnDelete();
            $table->foreignId('stand_id')->nullable()->constrained('stands')->nullOnDelete();
            $table->foreignId('evenement_id')->constrained('evenements')->cascadeOnDelete();

            $table->string('nom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->unsignedTinyInteger('nb_places')->default(1);
            $table->string('mode_paiement')->nullable(); // sur_place | wave | orange
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->dateTime('date_reservation')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente | en_attente_paiement | confirmee | annulee
            $table->string('reference')->nullable()->unique();

    $table->timestamps();
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
