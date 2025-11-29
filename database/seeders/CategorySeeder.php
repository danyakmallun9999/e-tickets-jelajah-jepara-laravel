<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pendidikan',
                'icon_class' => 'fa-solid fa-graduation-cap',
                'color' => '#3b82f6', // Blue
            ],
            [
                'name' => 'Kesehatan',
                'icon_class' => 'fa-solid fa-hospital',
                'color' => '#ef4444', // Red
            ],
            [
                'name' => 'Tempat Ibadah',
                'icon_class' => 'fa-solid fa-mosque',
                'color' => '#10b981', // Green
            ],
            [
                'name' => 'Pemerintahan',
                'icon_class' => 'fa-solid fa-building-columns',
                'color' => '#f59e0b', // Amber
            ],
            [
                'name' => 'Ekonomi & Bisnis',
                'icon_class' => 'fa-solid fa-store',
                'color' => '#8b5cf6', // Purple
            ],
            [
                'name' => 'Pariwisata & Budaya',
                'icon_class' => 'fa-solid fa-camera',
                'color' => '#ec4899', // Pink
            ],
            [
                'name' => 'Olahraga',
                'icon_class' => 'fa-solid fa-futbol',
                'color' => '#f97316', // Orange
            ],
            [
                'name' => 'Keamanan',
                'icon_class' => 'fa-solid fa-shield-halved',
                'color' => '#64748b', // Slate
            ],
            [
                'name' => 'Fasilitas Umum',
                'icon_class' => 'fa-solid fa-users',
                'color' => '#06b6d4', // Cyan
            ],
            [
                'name' => 'Pertanian & Peternakan',
                'icon_class' => 'fa-solid fa-wheat',
                'color' => '#84cc16', // Lime
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'icon_class' => $category['icon_class'],
                    'color' => $category['color']
                ]
            );
        }
    }
}
