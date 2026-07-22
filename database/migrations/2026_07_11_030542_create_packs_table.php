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
    Schema::create('packs', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('prix', 10, 2)->default(0);
        $table->unsignedTinyInteger('ordre')->default(0);

        $table->unsignedInteger('max_evenements')->nullable(); // null = illimité
        $table->boolean('mise_en_avant')->default(false);
        $table->boolean('stats_avancees')->default(false);
        $table->boolean('support_dedie')->default(false);

        $table->string('couleur')->nullable(); // ex: #1E5FD8 pour badge UI
        $table->boolean('actif')->default(true);
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
