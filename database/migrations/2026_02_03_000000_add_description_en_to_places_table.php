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
        if (!Schema::hasColumn('places', 'description_en')) {
            Schema::table('places', function (Blueprint $table) {
                $table->text('description_en')->nullable()->after('description');
            });
        }

        // 2. Populate data
        $translations = [
            'Pantai Kartini' => 'Jepara\'s main icon featuring a crossing pier to Karimunjawa and the giant turtle aquarium.',
            'Pantai Bandengan' => 'White sand beach with complete water sport facilities and beautiful sunset views.',
            'Songgolangit' => 'A towering waterfall surrounded by natural cliffs and lush greenery.',
            'Karimunjawa' => 'Marine National Park featuring the best snorkeling spots and pristine islands.',
            'Pulau Panjang' => 'A small island with white sands perfect for snorkeling and camping, accessible from Kartini Beach.',
            'Pantai Blebak' => 'A shallow and calm beach, safe for children and families.',
            'Pantai Teluk Awur' => 'Popular spot for watching sunsets by the beach, close to the town center.',
            'Pantai Empu Rancak' => 'Culinary center for Jepara\'s signature grilled fish with a relaxed beach atmosphere.',
            'Pantai Pailus' => 'A "virgin" beach that is very beautiful and largely untouched by buildings.',
            'Pantai Bringin' => 'A shady beach lined with large banyan trees in the northern part of Jepara.',
            'Pantai Bondo' => 'A beach with no waves (Dead Waves) featuring trendy cafes and Bali-like vibes.',
            'Pulau Mandalika' => 'An island in front of the Portuguese Fort featuring an old lighthouse.',
            'Telaga Sejuta Akar' => 'A natural spring surrounded by the giant roots of ancient rubber trees.',
            'Goa Manik Pecatu' => 'A hill by the beach offering expansive views of the open sea.',
            'Goa Tritip' => 'A natural cave rich with spiritual atmosphere, often visited for pilgrimage.',
            'Pantai Semat' => 'A fisherman\'s beach with an aesthetic wooden pier, famous for "Sego Menir" culinary.',
            'Pantai Seribu Ranting' => 'A unique beach decorated naturally with wooden twigs, very Instagrammable.',
            'Benteng Portugis' => 'Historical fortress ruins on a rock hill overlooking Mandalika Island.',
            'Taman Ari - Ari Kartini' => 'The site where RA Kartini\'s placenta was buried in Mayong, a historical education spot.',
            'Museum Kartini' => 'Gallery of RA Kartini\'s relics and the history of Jepara, located in the city center.',
            'Hari Jadi Jepara' => 'Annual city celebration featuring a grand cultural parade centered at the Regency Hall.',
            'Sonder (Pertapaan Ratu Kalinyamat)' => 'Protected forest area used as a historical hermitage, offering a quiet atmosphere.',
            'Klenteng Hian Thian ST' => 'Historical temple and center of Chinese culture, one of the oldest in the region.',
            'Gong Perdamaian' => 'World peace monument in Plajan Village housing a collection of world gongs.',
            'Makam Mantingan' => 'Tomb complex of Ratu Kalinyamat and an ancient mosque with distinct architecture.',
            'Perang Obor' => 'Tradition of fire fighting using coconut frond torches in Tegalsambi village.',
            'Jembul Tulakan' => 'Unique earth alms tradition featuring a parade, native to Tulakan Village.',
            'Baratan' => 'Traditional lantern procession commemorating Ratu Kalinyamat held on 15 Sha\'ban.',
            'Lomban' => 'Sea party involving the floating of a buffalo head, celebrated as the fisherman\'s holiday.',
            'Kura-Kura Ocean Park' => 'Giant turtle-shaped building housing a marine aquarium and educational facilities.',
            'Pungkruk' => 'Seafood culinary area with gazebos over the water, perfect for dinner.',
            'WB. Tiara Park' => 'Family waterboom with complete rides, popular for children\'s recreation.',
            'JOP (Ourland Park)' => 'The largest waterpark in Central Java with a Middle Eastern palace theme.',
            'Industri Mulyoharjo' => 'Center for wood sculpture and high-quality carving crafts.',
            'Desa Wisata Tempur' => 'Mountain valley village with coffee plantations and rice terraces, known as the "Swiss of Jepara".',
            'Desa Petekeyan' => 'Kampoeng Sembada Ukir, a center for local wood carvers and tourist education.',
            'Desa Troso' => 'Center for the world-famous Troso Ikat Weaving industry.',
            'Desa Wisata Kunir' => 'Nature-based tourism village offering mountain farming experiences.',
            'Desa Wisata Tanjung' => 'Tourism village based on local culture and history near Mount Muria.',
        ];

        foreach ($translations as $name => $descEn) {
            DB::table('places')
                ->where('name', $name)
                ->update(['description_en' => $descEn]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('places', 'description_en')) {
            Schema::table('places', function (Blueprint $table) {
                $table->dropColumn('description_en');
            });
        }
    }
};
