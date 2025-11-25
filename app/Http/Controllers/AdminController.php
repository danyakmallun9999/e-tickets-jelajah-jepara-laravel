<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(): View
    {
        $places = Place::with('category')->latest()->paginate(10);

        return view('admin.dashboard', compact('places'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $place = new Place([
            'latitude' => -6.7289,
            'longitude' => 110.7485,
        ]);

        return view('admin.places.create', compact('categories', 'place'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePlace($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeImage($request);
        }

        unset($data['image']);

        Place::create($data);

        return redirect()
            ->route('admin.places.index')
            ->with('status', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Place $place): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.places.edit', compact('categories', 'place'));
    }

    public function update(Request $request, Place $place): RedirectResponse
    {
        $data = $this->validatePlace($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($place->image_path);
            $data['image_path'] = $this->storeImage($request);
        }

        unset($data['image']);

        $place->update($data);

        return redirect()
            ->route('admin.places.index')
            ->with('status', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Place $place): RedirectResponse
    {
        if ($place->image_path) {
            $this->deleteImage($place->image_path);
        }

        $place->delete();

        return redirect()
            ->route('admin.places.index')
            ->with('status', 'Lokasi berhasil dihapus.');
    }

    protected function validatePlace(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    protected function storeImage(Request $request): string
    {
        $path = $request->file('image')->store('places', 'public');

        return 'storage/' . $path;
    }

    protected function deleteImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        $cleanPath = str_replace('storage/', '', $path);

        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }
}
