<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(CategorySeeder::class);

        // Fetch Categories
        $edu = \App\Models\Category::where('name', 'Pendidikan')->first();
        $worship = \App\Models\Category::where('name', 'Tempat Ibadah')->first();
        $gov = \App\Models\Category::where('name', 'Pemerintahan')->first();

        // Places
        if ($edu) {
            \App\Models\Place::create([
                'category_id' => $edu->id,
                'name' => 'SD Negeri 1 Mayong Lor',
                'description' => 'Sekolah Dasar Negeri unggulan di desa Mayong Lor.',
                'latitude' => -6.7289,
                'longitude' => 110.7485,
                'image_path' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80'
            ]);
        }

        if ($worship) {
            \App\Models\Place::create([
                'category_id' => $worship->id,
                'name' => 'Masjid Jami Al-Hikmah',
                'description' => 'Masjid besar pusat kegiatan keagamaan warga.',
                'latitude' => -6.7300,
                'longitude' => 110.7500,
                'image_path' => 'https://images.unsplash.com/photo-1564121211835-e88c852648ab?auto=format&fit=crop&w=800&q=80'
            ]);
        }
        
        if ($gov) {
            \App\Models\Place::create([
                'category_id' => $gov->id,
                'name' => 'Balai Desa Mayong Lor',
                'description' => 'Pusat pelayanan administrasi desa.',
                'latitude' => -6.7280,
                'longitude' => 110.7480,
                'image_path' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80'
            ]);
        }
    }
}
