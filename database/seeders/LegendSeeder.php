<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama jika perlu
        DB::table('legends')->truncate();

        $legends = [
            [
                'name' => 'Ratu Shima',
                'image' => 'images/legenda/shima.jpg',
                'quote_id' => '"Keadilan Tanpa Pandang Bulu"',
                'quote_en' => '"Justice Without Prejudice"',
                'description_id' => 'Penguasa Kerajaan Kalingga yang termasyhur akan ketegasan hukumnya. Simbol integritas dan keadilan sejati dari masa lampau.',
                'description_en' => 'The ruler of the Kalingga Kingdom, renowned for her strict laws. A symbol of true integrity and justice from the past.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ratu Kalinyamat',
                'image' => 'images/legenda/kalinyamat.jpg',
                'quote_id' => '"Sang Ratu Laut yang Gagah Berani"',
                'quote_en' => '"The Brave Queen of the Sea"',
                'description_id' => 'Penguasa maritim Nusantara yang disegani. Membangun Jepara menjadi pusat niaga dan kekuatan laut yang tak tertandingi.',
                'description_en' => 'A respected maritime ruler of the archipelago. She built Jepara into a center of commerce and unmatched naval power.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'R.A. Kartini',
                'image' => 'images/legenda/kartini.jpg',
                'quote_id' => '"Habis Gelap Terbitlah Terang"',
                'quote_en' => '"Out of Dark Comes Light"',
                'description_id' => 'Pahlawan emansipasi yang memperjuangkan hak pendidikan wanita. Sosoknya menginspirasi perubahan besar dari Jepara untuk Indonesia.',
                'description_en' => 'An emancipation hero who fought for women\'s educational rights. Her figure inspired great change from Jepara for Indonesia.',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($legends as $legend) {
            \App\Models\Legend::create($legend);
        }
    }
}
