<?php

namespace App\Http\Controllers;

use App\Models\Population;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopulationController extends Controller
{
    public function index(): View
    {
        $population = Population::first();

        if (! $population) {
            $population = new Population;
        }

        return view('admin.population.index', compact('population'));
    }

    public function edit(): View
    {
        $population = Population::first();

        if (! $population) {
            $population = new Population;
        }

        return view('admin.population.edit', compact('population'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'total_population' => 'required|integer|min:0',
            'total_families' => 'required|integer|min:0',
            'total_male' => 'required|integer|min:0',
            'total_female' => 'required|integer|min:0',
            'age_groups' => 'nullable|array',
            'education_levels' => 'nullable|array',
            'jobs' => 'nullable|array',
            'religions' => 'nullable|array',
        ]);

        $population = Population::first();

        if ($population) {
            $population->update($validated);
        } else {
            Population::create($validated);
        }

        return redirect()->route('admin.population.index')->with('status', 'Data penduduk berhasil diperbarui.');
    }
}
