<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FixMissingSlugsSeeder extends Seeder
{
    public function run()
    {
        $places = Place::whereNull('slug')->orWhere('slug', '')->get();
        foreach ($places as $place) {
            $slug = Str::slug($place->name);
            // Ensure uniqueness
            $count = Place::where('slug', 'LIKE', "{$slug}%")->count();
            if ($count > 0) {
                $slug .= '-'.($count + 1);
            }
            $place->update(['slug' => $slug]);
        }
    }
}
