<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Wisata Alam',
                'slug' => 'wisata-alam',
                'icon_class' => 'fa-solid fa-umbrella-beach',
                'color' => '#0ea5e9', // Ocean Blue
            ],
            [
                'name' => 'Wisata Budaya & Sejarah',
                'slug' => 'wisata-budaya',
                'icon_class' => 'fa-solid fa-monument',
                'color' => '#8b5a2b', // Wood Brown
            ],
            [
                'name' => 'Wisata Kuliner',
                'slug' => 'wisata-kuliner',
                'icon_class' => 'fa-solid fa-utensils',
                'color' => '#f97316', // Orange
            ],
            [
                'name' => 'Akomodasi',
                'slug' => 'akomodasi',
                'icon_class' => 'fa-solid fa-hotel',
                'color' => '#6366f1', // Indigo
            ],
            [
                'name' => 'Ekonomi Kreatif',
                'slug' => 'ekraf',
                'icon_class' => 'fa-solid fa-palette',
                'color' => '#ec4899', // Pink
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
