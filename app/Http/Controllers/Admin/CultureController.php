<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use App\Models\CultureImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CultureController extends Controller
{
    private function authorizeSuperAdmin()
    {
        if (!auth('admin')->user()?->hasRole('super_admin')) {
            abort(403, 'Unauthorized access.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $categories = Culture::select('category')->distinct()->pluck('category');

        $cultures = Culture::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->latest()
            ->paginate(10);

        return view('admin.cultures.index', compact('cultures', 'categories'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();
        $categories = \App\Models\Category::all();
        return view('admin.cultures.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'image'       => 'nullable|image|max:4096',
            'images.*'    => 'nullable|image|max:4096',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'content'     => 'nullable|string',
            'content_en'  => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'time'        => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:500',
            'locations.*.name' => 'required|string|max:255',
            'locations.*.category_id' => 'required|exists:categories,id',
            'locations.*.address' => 'nullable|string|max:255',
            'locations.*.google_maps_url' => 'nullable|url|max:1000',
            'locations.*.open_time' => 'nullable|date_format:H:i',
            'locations.*.close_time' => 'nullable|date_format:H:i',
        ]);

        $data = $request->only(['name','category','description','description_en','content','content_en','location','time','youtube_url']);

        if ($request->filled('image_gallery_url')) {
            // Extract relative path if it's a full URL, assuming it's from the public disk
            $fullUrl = $request->input('image_gallery_url');
            $baseUrl = url('/');
            if (Str::startsWith($fullUrl, $baseUrl)) {
                $data['image'] = Str::after($fullUrl, $baseUrl . '/storage/');
            } else {
                $data['image'] = $fullUrl; // Store as is if not a local URL
            }
        } elseif ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/culture', 'public');
        }

        $culture = Culture::create($data);

        // Simpan foto-foto tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images/culture', 'public');
                $culture->images()->create(['image_path' => $path]);
            }
        }

        // Simpan lokasi rekomendasi
        if ($request->has('locations')) {
            foreach ($request->locations as $loc) {
                if (!empty($loc['name'])) {
                    $culture->locations()->create($loc);
                }
            }
        }

        return redirect()->route('admin.cultures.index')->with('success', 'Budaya berhasil ditambahkan.');
    }

    public function edit(Culture $culture)
    {
        $this->authorizeSuperAdmin();
        $culture->load(['images', 'locations']);
        $categories = \App\Models\Category::all();
        return view('admin.cultures.edit', compact('culture', 'categories'));
    }

    public function update(Request $request, Culture $culture)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'image'       => 'nullable|image|max:4096',
            'images.*'    => 'nullable|image|max:4096',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'content'     => 'nullable|string',
            'content_en'  => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'time'        => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:500',
            'locations.*.name' => 'required|string|max:255',
            'locations.*.category_id' => 'required|exists:categories,id',
            'locations.*.address' => 'nullable|string|max:255',
            'locations.*.google_maps_url' => 'nullable|url|max:1000',
            'locations.*.open_time' => 'nullable|date_format:H:i',
            'locations.*.close_time' => 'nullable|date_format:H:i',
        ]);

        $data = $request->only(['name','category','description','description_en','content','content_en','location','time','youtube_url']);

        if ($request->filled('image_gallery_url')) {
            if ($culture->image && !Str::startsWith($culture->image, 'http')) { // Only delete if it's a local file, not an external URL
                Storage::disk('public')->delete($culture->image);
            }
            // Extract relative path if it's a full URL, assuming it's from the public disk
            $fullUrl = $request->input('image_gallery_url');
            $baseUrl = url('/');
            if (Str::startsWith($fullUrl, $baseUrl)) {
                $data['image'] = Str::after($fullUrl, $baseUrl . '/storage/');
            } else {
                $data['image'] = $fullUrl; // Store as is if not a local URL
            }
        } elseif ($request->hasFile('image')) {
            if ($culture->image && !Str::startsWith($culture->image, 'http')) { // Only delete if it's a local file, not an external URL
                Storage::disk('public')->delete($culture->image);
            }
            $data['image'] = $request->file('image')->store('images/culture', 'public');
        }

        $culture->update($data);

        // Simpan foto-foto tambahan baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images/culture', 'public');
                $culture->images()->create(['image_path' => $path]);
            }
        }

        // Simpan/Update lokasi rekomendasi
        $culture->locations()->delete();
        if ($request->has('locations')) {
            foreach ($request->locations as $loc) {
                if (!empty($loc['name'])) {
                    $culture->locations()->create($loc);
                }
            }
        }

        return redirect()->route('admin.cultures.index')->with('success', 'Budaya berhasil diperbarui.');
    }

    public function destroy(Culture $culture)
    {
        $this->authorizeSuperAdmin();

        if ($culture->image) {
            Storage::disk('public')->delete($culture->image);
        }

        // Hapus semua foto tambahan
        foreach ($culture->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $culture->delete();

        return redirect()->route('admin.cultures.index')->with('success', 'Budaya berhasil dihapus.');
    }

    public function destroyImage(CultureImage $image)
    {
        $this->authorizeSuperAdmin();

        Storage::disk('public')->delete($image->image_path);
        $cultureId = $image->culture_id;
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
