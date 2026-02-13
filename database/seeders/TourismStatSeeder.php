<?php

namespace Database\Seeders;

use App\Models\TourismStat;
use Illuminate\Database\Seeder;

class TourismStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = now()->year;
        $months = range(1, 12);

        foreach ($months as $month) {
            // Only seed up to current month if strict, but for demo let's seed all or up to now
            if ($month > now()->month) break;

            TourismStat::updateOrCreate(
                ['month' => $month, 'year' => $currentYear],
                ['visitors' => rand(5000, 15000)]
            );
        }
    }
}
