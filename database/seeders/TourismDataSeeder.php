<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Place;
use Illuminate\Support\Str;

class TourismDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('data_pariwisata.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error("File public/data_pariwisata.json not found!");
            return;
        }

        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        if (!isset($data['data_pariwisata'])) {
            $this->command->error("Invalid JSON format");
            return;
        }

        foreach ($data['data_pariwisata'] as $item) {
            // Skip "Lain-lain" or empty placeholders if needed
            if ($item['nama_wisata'] === 'Lain-lain' || $item['kategori'] === '-') {
                continue;
            }

            // 1. Find or Create Category
            $categoryName = $item['kategori'];
            $categorySlug = Str::slug($categoryName);

            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                [
                    'name' => $categoryName,
                    'icon_class' => 'fas fa-map-marker-alt', // Default icon
                    'color' => '#3b82f6', // Default blue
                ]
            );

            // 2. Create Place
            // Generate basic slug
            $slug = Str::slug($item['nama_wisata']);
            
            // Check for duplicate slug, append random if exists (simple check)
            if (Place::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            // Parse Link if reasonable, but defaulting to Jepara Center
            // Jepara Center: -6.5817679, 110.6698188
            $lat = -6.5817679;
            $long = 110.6698188;

            Place::updateOrCreate(
                ['name' => $item['nama_wisata']], // Check by name to avoid duplicates if re-run
                [
                    'category_id' => $category->id,
                    'slug' => $slug,
                    'address' => $item['lokasi'],
                    'google_maps_link' => $item['titi_koordinat'],
                    'opening_hours' => $item['jam_operasional'],
                    'ticket_price' => $item['harga_tiket'],
                    'description' => $item['deskripsi'],
                    'notes' => $item['noted'],
                    'image_path' => null, // No image in JSON
                    'latitude' => $lat,
                    'longitude' => $long,
                ]
            );

            $this->command->info("Imported: " . $item['nama_wisata']);
        }
    }
}
