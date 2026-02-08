<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place;

class EnglishPlacesSeeder extends Seeder
{
    public function run()
    {
        $translations = [
            'Pantai Kartini' => [
                'name_en' => 'Kartini Beach',
                'description_en' => 'Kartini Beach is a strategic recreation area located 2.5 km west of the Jepara Regency Hall. Covering an area of 3.5 hectares, it serves as a stopover for tourists heading to Karimunjawa. Attractions include the Kura-Kura Ocean Park, children\'s playground, mini train, and boat tours to Panjang Island.',
            ],
            'Museum RA. Kartini' => [
                'name_en' => 'R.A. Kartini Museum',
                'description_en' => 'Museum dedicated to R.A. Kartini, an Indonesian national hero. It houses a collection of her relics, including letters and photographs, as well as ancient ceramics and artifacts found in the Jepara area. A center for cultural and historical learning.',
            ],
            'Pantai Tirta Samudra (Bandengan)' => [
                'name_en' => 'Tirta Samudra Beach (Bandengan)',
                'description_en' => 'Known for its pristine white sand and clear waters. Offers various water sports activities such as jet skiing, banana boats, and canoeing. A perfect spot for family picnics and events, located about 7 km from Jepara city center.',
            ],
            'Jepara Ourland Park' => [
                'name_en' => 'Jepara Ourland Park',
                'description_en' => 'The largest integrated water park in Central Java with a Middle Eastern theme. Features 36 thrilling slides, a lazy river, wave pool, and various other rides. Suitable for all ages and offers complete facilities including dining and locker rooms.',
            ],
            'Pantai Teluk Awur Jepara' => [
                'name_en' => 'Teluk Awur Beach',
                'description_en' => 'A family-friendly beach known for its traditional "Lomban" festival celebrated annually by locals. The waters are calm and safe for swimming. The beach is lined with mangroves which help prevent abrasion.',
            ],
            'Pantai Blebak' => [
                'name_en' => 'Blebak Beach',
                'description_en' => 'Located in Sekuro Village, this beach offers calm waves suitable for children and families. Features clean white sand and is surrounded by lush greenery. Water bikes and canoeing are popular activities here.',
            ],
            'Pulau Panjang' => [
                'name_en' => 'Panjang Island',
                'description_en' => 'A small tropical island located just a short boat ride from Kartini Beach. Famous for its shallow, crystal-clear waters perfect for snorkeling and camping. The island is surrounded by a coral reef and features a dense tropical forest.',
            ],
            'Benteng Portugis' => [
                'name_en' => 'Portuguese Fort',
                'description_en' => 'A historical fortress built in 1632 overlooking Mandalika Island. It served as a defense post. While the ruins are partial, the site offers breathtaking views of the sea and hills. A mix of history and nature tourism.',
            ],
            'Gua Manik' => [
                'name_en' => 'Manik Cave',
                'description_en' => 'Despite its name, Gua Manik is more popular for its hill and beach scenery in Donorojo. It offers panoramic views of the Java Sea from atop a hill. Ideal for sunrise and sunset viewing.',
            ],
            'Air Terjun Songgo Langit' => [
                'name_en' => 'Songgo Langit Waterfall',
                'description_en' => 'A stunning 80-meter high waterfall located in Bucu village. The name "Songgo Langit" means "Supporting the Sky". The area is known for its refreshing atmosphere and the legend of the thousand butterflies.',
            ],
            'Wisata Telaga Harun Somosari' => [
                'name_en' => 'Lake Harun Somosari',
                'description_en' => 'An artificial lake in Somosari village offering cool mountain air and scenic views. Popular for fishing and relaxing. The surrounding area allows for trekking and exploring the rural nature of Jepara.',
            ],
            'Gua Tritip' => [
                'name_en' => 'Tritip Cave',
                'description_en' => 'A historical cave located in Ujungwatu. Believed to be a place of meditation in the past. The site is currently being developed as a religious and cultural tourism destination.',
            ],
            'Pulau Mandalika' => [
                'name_en' => 'Mandalika Island',
                'description_en' => 'An uninhabited island opposite the Portuguese Fort. Known for its rich underwater biodiversity, making it a favorite spot for fishing and diving. The island has endemic plants and a lighthouse.',
            ],
            'Wisata Desa Tempur' => [
                'name_en' => 'Tempur Village Tourism',
                'description_en' => 'A tourist village nestled in the Muria mountains, surrounded by coffee plantations and rice terraces. Often called the "Hidden Paradise of Jepara". Offers river tubing, trekking, and authentic local coffee experiences.',
            ],
            'Wana Wisata Sreni Indah' => [
                'name_en' => 'Sreni Indah Forest',
                'description_en' => 'A protected pine forest located in Bategede. It provides a cool, Instagrammable atmosphere with rows of tall pine trees. Perfect for camping, outbond activities, and nature photography.',
            ],
            'Pasar Sore Karangrandu (PSK)' => [
                'name_en' => 'Karangrandu Afternoon Market',
                'description_en' => 'A culinary destination specializing in traditional Jepara snacks like Adon-Adon Coro, Horog-Horog, and various traditional cakes. Open in the afternoons, offering a nostalgic rural market vibe.',
            ],
            'Tiara Park Waterboom' => [
                'name_en' => 'Tiara Park Waterboom',
                'description_en' => 'A water park located in Purwogondo, offering affordable family fun. Features splashing pools, water slides, and 3D cinema. A popular weekend getaway for locals.',
            ],
            'Makam Mantingan' => [
                'name_en' => 'Mantingan Tomb',
                'description_en' => 'The final resting place of Queen Kalinyamat and Sultan Hadlirin. The mosque features distinct Hindu-style carvings, symbolizing cultural acculturation. A significant pilgrimage site for religious tourism.',
            ],
            'Wisata Kali Ndayung' => [
                'name_en' => 'Kali Ndayung Tourism',
                'description_en' => 'A nature tourism spot in Somosari featuring a flowing river and small waterfalls. Ideal for river trekking and enjoying the fresh mountain water.',
            ],
        ];

        foreach ($translations as $indonesianName => $data) {
            $place = Place::where('name', $indonesianName)->first();

            if ($place) {
                $place->update([
                    'name_en' => $data['name_en'],
                    'description_en' => $data['description_en'],
                ]);
            }
        }
    }
}
