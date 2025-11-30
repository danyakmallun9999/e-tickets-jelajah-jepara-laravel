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
        Schema::table('populations', function (Blueprint $table) {
            $table->json('age_groups')->nullable()->after('total_female');
            $table->json('education_levels')->nullable()->after('age_groups');
            $table->json('jobs')->nullable()->after('education_levels');
            $table->json('religions')->nullable()->after('jobs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('populations', function (Blueprint $table) {
            $table->dropColumn(['age_groups', 'education_levels', 'jobs', 'religions']);
        });
    }
};
