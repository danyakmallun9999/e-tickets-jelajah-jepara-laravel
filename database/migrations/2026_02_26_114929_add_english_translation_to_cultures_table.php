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
        Schema::table('cultures', function (Blueprint $table) {
            if (!Schema::hasColumn('cultures', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('cultures', 'content_en')) {
                $table->longText('content_en')->nullable()->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cultures', function (Blueprint $table) {
            if (Schema::hasColumn('cultures', 'description_en')) {
                $table->dropColumn('description_en');
            }
            if (Schema::hasColumn('cultures', 'content_en')) {
                $table->dropColumn('content_en');
            }
        });
    }
};
