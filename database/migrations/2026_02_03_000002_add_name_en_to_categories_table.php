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
        // 1. Add the column
        if (!Schema::hasColumn('categories', 'name_en')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name_en')->nullable()->after('name');
            });
        }

        // 2. Populate data
        $translations = [
            'Wisata Alam' => 'Nature Tourism',
            'Wisata Sejarah' => 'Historical Tourism',
            'Wisata Budaya' => 'Cultural Tourism',
            'Wisata Buatan' => 'Artificial Tourism',
            'Desa Pariwisata' => 'Tourism Village',
        ];

        foreach ($translations as $name => $nameEn) {
            DB::table('categories')
                ->where('name', $name)
                ->update(['name_en' => $nameEn]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'name_en')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('name_en');
            });
        }
    }
};
