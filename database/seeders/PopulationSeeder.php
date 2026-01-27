<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('populations')->truncate();

        DB::table('populations')->insert([
            'total_population' => 1234567, // Example Jepara population
            'total_male' => 610000,
            'total_female' => 624567,
            'total_families' => 350000,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
