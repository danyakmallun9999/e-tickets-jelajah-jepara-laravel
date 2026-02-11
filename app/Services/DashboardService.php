<?php

namespace App\Services;


use App\Models\Category;
use App\Models\Event;
use App\Models\Infrastructure;
use App\Models\LandUse;
use App\Models\Place;
use App\Models\Post;

class DashboardService
{
    /**
     * Get all statistics for the admin dashboard.
     */
    public function getDashboardStats(): array
    {
        return [
            'places_count' => Place::count(),
            'infrastructures_count' => Infrastructure::count(),
            'land_uses_count' => LandUse::count(),
            'categories' => Category::withCount('places')->get(),
            'infrastructure_types' => Infrastructure::selectRaw('type, COUNT(*) as count, SUM(length_meters) as total_length')
                ->groupBy('type')
                ->get(),
            'land_use_types' => LandUse::selectRaw('type, COUNT(*) as count, SUM(area_hectares) as total_area')
                ->groupBy('type')
                ->get(),
            'total_land_use_area' => LandUse::sum('area_hectares'),
            'total_infrastructure_length' => Infrastructure::sum('length_meters'),
            'recent_places' => Place::with('category')->latest()->take(5)->get(),
            'recent_infrastructures' => Infrastructure::latest()->take(5)->get(),
            'recent_land_uses' => LandUse::latest()->take(5)->get(),
            'posts_count' => Post::count(),
            'events_count' => Event::count(),
            'recent_posts' => Post::latest('published_at')->take(5)->get(),
            'upcoming_events' => Event::where('start_date', '>=', now())->orderBy('start_date')->take(5)->get(),
        ];
    }
}
