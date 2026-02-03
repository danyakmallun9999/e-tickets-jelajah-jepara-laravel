<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoundarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('boundaries')->truncate();

        $kecamatans = [
            'Jepara', 'Tahunan', 'Pecangaan', 'Kalinyamatan', 'Welahan',
            'Mayong', 'Nalumsari', 'Batealit', 'Kedung', 'Mlonggo',
            'Bangsri', 'Kembang', 'Keling', 'Donorojo', 'Pakis Aji', 'Karimunjawa',
        ];

        foreach ($kecamatans as $kecamatan) {
            DB::table('boundaries')->insert([
                'name' => 'Kecamatan '.$kecamatan,
                'type' => 'Kecamatan',
                'description' => 'Wilayah administratif Kecamatan '.$kecamatan,
                'area_hectares' => 6275.00, // Average for demo: 100,400 / 16
                'geometry' => json_encode(['type' => 'Polygon', 'coordinates' => []]), // Dummy geometry
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
