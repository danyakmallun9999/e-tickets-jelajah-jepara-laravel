<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::withCount('places')->get();
        $places = \App\Models\Place::with('category')->get();

        return view('welcome', compact('categories', 'places'));
    }
}
