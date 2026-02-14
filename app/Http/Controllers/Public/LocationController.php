<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Facade as Indonesia;

class LocationController extends Controller
{
    public function provinces()
    {
        try {
            $json = \Illuminate\Support\Facades\File::get(storage_path('app/json/provinces.json'));
            $provinces = json_decode($json, true);
            // Sort by name
            usort($provinces, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cities(Request $request)
    {
        try {
            $provinceId = $request->query('province_id');
            if (!$provinceId) {
                return response()->json(['error' => 'Province ID is required'], 400);
            }

            $json = \Illuminate\Support\Facades\File::get(storage_path('app/json/cities.json'));
            $allCities = json_decode($json, true);

            $cities = array_filter($allCities, function ($city) use ($provinceId) {
                return $city['province_id'] == $provinceId;
            });

            // Sort by name
            usort($cities, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            return response()->json(array_values($cities));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
