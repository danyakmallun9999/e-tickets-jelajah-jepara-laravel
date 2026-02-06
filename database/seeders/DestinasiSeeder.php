<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Place;
use App\Models\Category;
use Illuminate\Support\Str;

class DestinasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = public_path('20-destinasi.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("File 20-destinasi.json not found in public folder.");
            return;
        }

        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        if (!$data || !isset($data['data_wisata'])) {
            $this->command->error("Invalid JSON structure.");
            return;
        }

        foreach ($data['data_wisata'] as $item) {
            // Determine Category
            // Split "jenis_wisata" by comma and take the first one
            $categoryName = 'Wisata Alam'; // Default
            if (!empty($item['jenis_wisata'])) {
                $categories = explode(',', $item['jenis_wisata']);
                $categoryName = trim($categories[0]);
            }

            // Find or Create Category
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'slug' => Str::slug($categoryName),
                    'icon_class' => 'fa-solid fa-map-marker-alt', // Default icon
                    'color' => '#0ea5e9' // Default color
                ]
            );

            // Update or Create Place
            $place = Place::updateOrCreate(
                ['name' => $item['nama_wisata']],
                [
                    'category_id' => $category->id,
                    'address' => $item['lokasi'] ?? null,
                    'kecamatan' => $item['kecamatan'] ?? null,
                    'description' => $item['deskripsi'] ?? null,
                    'latitude' => $item['latitude'] ?? 0,
                    'longitude' => $item['longitude'] ?? 0,
                    'ticket_price' => $item['harga_tiket'] ?? null,
                    'opening_hours' => $item['waktu_buka'] ?? null,
                    'google_maps_link' => $item['link_koordinat'] ?? null,
                    'ownership_status' => $item['status_kepemilikan'] ?? null,
                    'manager' => $item['pengelola'] ?? null,
                    'rides' => $item['wahana'] ?? [],
                    'facilities' => $item['fasilitas'] ?? [],
                    'social_media' => $item['media_sosial'] ?? null,
                ]
            );

            $this->command->info("Processed: " . $place->name);
        }
    }
}
