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
        // Fix for "Air Terjun Songgo Langit" (Previously mapped as "Songgolangit")
        DB::table('places')
            ->where('name', 'LIKE', '%Songgo Langit%')
            ->update([
                'description_en' => 'Songgo Langit Waterfall is one of the most mesmerizing nature tourism destinations, offering a towering waterfall view surrounded by natural cliffs and lush greenery.'
            ]);

        // Fix for "Andina Swimming Pool"
        DB::table('places')
            ->where('name', 'LIKE', '%Andina Swimming Pool%')
            ->update([
                'description_en' => 'Andina Swimming Pool is a popular swimming destination recommended for families, offering clean water and comfortable facilities.'
            ]);

        // Fix for "Wisata Alam Watu Lawang Jepara"
        DB::table('places')
            ->where('name', 'LIKE', '%Watu Lawang%')
            ->update([
                'description_en' => 'Watu Lawang Nature Tourism is a destination offering beautiful scenic views of Jepara from the heights, perfect for nature lovers and photography.'
            ]);
            
        // Generic fix for any other missing ones if possible (Fallback)
        // Check standard matching again for variations
        $corrections = [
            'Pantai Kartini' => 'Jepara\'s main icon featuring a crossing pier to Karimunjawa and the giant turtle aquarium.',
            'Karimunjawa' => 'Marine National Park featuring the best snorkeling spots and pristine islands.',
        ];

        foreach ($corrections as $name => $desc) {
             DB::table('places')
            ->where('name', 'LIKE', "%$name%")
            ->whereNull('description_en')
            ->update(['description_en' => $desc]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse text updates
    }
};
