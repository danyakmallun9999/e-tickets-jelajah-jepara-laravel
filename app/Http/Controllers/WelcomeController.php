<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Place;
use Illuminate\Http\JsonResponse;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('places')->get();

        return view('welcome', compact('categories'));
    }

    public function geoJson(): JsonResponse
    {
        $features = Place::with('category')
            ->get()
            ->map(function (Place $place) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $place->id,
                        'name' => $place->name,
                        'description' => $place->description,
                        'image_url' => $place->image_path ? asset($place->image_path) : null,
                        'category' => [
                            'id' => $place->category?->id,
                            'name' => $place->category?->name,
                            'color' => $place->category?->color,
                            'icon_class' => $place->category?->icon_class,
                        ],
                    ],
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float) $place->longitude,
                            (float) $place->latitude,
                        ],
                    ],
                ];
            });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
