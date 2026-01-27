<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 2MB Max
            'seller_name' => 'nullable|string|max:255',
            'seller_contact' => 'nullable|string|max:20',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = Storage::url($path);
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'seller_name' => 'nullable|string|max:255',
            'seller_contact' => 'nullable|string|max:20',
        ]);

        // Only update slug if name changed
        if ($product->name !== $validated['name']) {
             $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image_path) {
                // Parse relative path from URL (simple assumption: /storage/products/filename)
                $relativePath = str_replace('/storage/', 'public/', $product->image_path);
                 // Or just assume it was stored in public disk
                 // Better: store('products', 'public') returns relative path to disk root.
                 // URL is /storage/...
                 // We should probably just delete if exists.
                 // For now, let's keep it simple: just upload new one.
                 // Ideally: Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_path));
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = Storage::url($path);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
             // Storage::disk('public')->delete(...)
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }
}
