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
    Schema::table('evenements', function (Blueprint $table) {
        if (!Schema::hasColumn('evenements', 'pack_id')) {
            $table->foreignId('pack_id')->nullable()->after('id')->constrained('packs')->nullOnDelete();
        }
        if (!Schema::hasColumn('evenements', 'organisateur_id')) {
            $table->foreignId('organisateur_id')->nullable()->after('pack_id')->constrained('users')->nullOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('evenements', function (Blueprint $table) {
        $table->dropForeign(['pack_id']);
        $table->dropColumn('pack_id');
        $table->dropForeign(['organisateur_id']);
        $table->dropColumn('organisateur_id');
    });
}
};
