<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DestinasiImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $places = Place::all();
        $imageDir = public_path('images/destinasi');

        if (! File::exists($imageDir)) {
            $this->command->error("Directory not found: $imageDir");

            return;
        }

        $files = File::files($imageDir);
        $totalImages = 0;

        // Stop words to ignore in matching (too common)
        $stopWords = ['pantai', 'wisata', 'air', 'terjun', 'pulau', 'desa', 'jepara', 'kabupaten', 'gunung', 'bukit', 'taman', 'dan', 'di', 'ke'];

        foreach ($places as $place) {
            $matchedFiles = [];

            // 1. Get Folder Path from Mapping
            // Mirroring the mapping from PariwisataSeeder for consistency
            $folderMapping = [
                'Pantai Kartini' => 'pantai-kartini',
                'Museum RA. Kartini' => 'museum-kartini',
                'Pantai Tirta Samudra (Bandengan)' => 'pantai-bandengan',
                'Jepara Ourland Park' => 'jepara-ourland-park',
                'Pantai Teluk Awur Jepara' => 'panti-teluk-awur', 
                'Pantai Blebak' => 'pantai-blebak',
                'Pulau Panjang' => 'pulau-panjang',
                'Benteng Portugis' => 'benteng-portugis',
                'Gua Manik' => 'gua-manik',
                'Air Terjun Songgo Langit' => 'songgo-langit',
                'Wisata Telaga Harun Somosari' => 'telaga-harun-somorsari',
                'Gua Tritip' => 'gua-tritip',
                'Pulau Mandalika' => 'pulau-mandalika',
                'Wisata Desa Tempur' => 'desa-tempur',
                'Wana Wisata Sreni Indah' => 'sreni',
                'Pasar Sore Karangrandu (PSK)' => 'pasar-karang-randu',
                'Tiara Park Waterboom' => 'tiara-park',
                'Makam Mantingan' => 'makam-mantingan',
                'Wisata Kali Ndayung' => 'kali-dayung',
                'Taman Nasional Karimunjawa' => 'karimun-jawa',
            ];

            $folderName = $folderMapping[$place->name] ?? null;

            if ($folderName) {
                $folderPath = public_path('images/destinasi/' . $folderName);
                
                if (File::exists($folderPath)) {
                    $files = File::files($folderPath);
                    $place->images()->delete(); // Clear existing gallery
                    
                    // Sort files to have consistent order (e.g. 0.jpg, 1.jpg, ...)
                    // Custom sort to handle numbers correctly if needed, but standard sort is usually fine for 0, 1, 2
                    $filenames = [];
                    foreach($files as $file) {
                        $filenames[] = $file->getFilename();
                    }
                    sort($filenames, SORT_NATURAL);

                    foreach ($filenames as $filename) {
                         // Filter for supported extensions
                        if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $filename)) {
                            $place->images()->create([
                                'image_path' => 'images/destinasi/' . $folderName . '/' . $filename,
                            ]);
                            $matchedFiles[] = $filename;
                            $totalImages++;
                        }
                    }
                } else {
                     $this->command->warn("Folder not found for {$place->name}: $folderName");
                }
            } else {
                // $this->command->warn("No folder mapping found for: {$place->name}");
            }
        }

        $this->command->info("Seeding complete. Mapped $totalImages images.");
    }
}
