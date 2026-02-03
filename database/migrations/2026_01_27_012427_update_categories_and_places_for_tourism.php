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
            $table->string('ticket_price')->nullable()->after('description'); // e.g., "Rb 10.000 (Weekend)"
            $table->string('opening_hours')->nullable()->after('ticket_price'); // e.g., "08:00 - 16:00"
            $table->string('contact_info')->nullable()->after('opening_hours'); // Phone or Email
            $table->decimal('rating', 3, 2)->default(0.00)->after('contact_info'); // 4.5
            $table->string('website')->nullable()->after('rating');
        });

        // Ensure slug exists on categories if it doesn't already (check first)
        if (! Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->unique()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['ticket_price', 'opening_hours', 'contact_info', 'rating', 'website']);
        });

        // We might not want to drop slug from categories if it was added manually elsewhere,
        // but for this migration scope:
        if (Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                // $table->dropColumn('slug'); // Safer to keep it consistent
            });
        }
    }
};
