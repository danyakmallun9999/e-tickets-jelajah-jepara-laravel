<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add icon_class if it doesn't exist
        if (! Schema::hasColumn('categories', 'icon_class')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('icon_class')->nullable()->after('name');
            });
        }

        // 2. Copy data from icon to icon_class if icon exists
        if (Schema::hasColumn('categories', 'icon')) {
            DB::statement('UPDATE categories SET icon_class = icon');

            // 3. Drop icon column
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }

        // 4. Update specific icons to ensure correctness
        DB::table('categories')->where('name', 'Pendidikan')->update(['icon_class' => 'fa-solid fa-graduation-cap']);
        DB::table('categories')->where('name', 'Tempat Ibadah')->update(['icon_class' => 'fa-solid fa-mosque']);
        DB::table('categories')->where('name', 'Pemerintahan')->update(['icon_class' => 'fa-solid fa-building-columns']);
    }
};
