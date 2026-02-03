<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UpdateSlugsSeeder extends Seeder
{
    public function run()
    {
        $places = Place::all();
        foreach ($places as $place) {
            $place->update(['slug' => Str::slug($place->name).'-'.strtolower(Str::random(5))]);
        }
    }
}
