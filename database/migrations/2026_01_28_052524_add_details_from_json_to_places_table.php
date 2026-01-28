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
        Schema::table('places', function (Blueprint $table) {
            $table->string('google_maps_link')->nullable()->after('longitude');
            $table->string('address')->nullable()->after('name'); // For "lokasi" (e.g. Jepara, Kembang)
            $table->text('notes')->nullable()->after('description'); // For "noted"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['google_maps_link', 'address', 'notes']);
        });
    }
};
