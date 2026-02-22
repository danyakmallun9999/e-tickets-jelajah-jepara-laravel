<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Gallery page — grid view with search, filter, upload.
     */
    public function index(Request $request): View
    {
        $query = Media::with('uploader')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('source')) {
            $query->fromSource($request->source);
        }

        $media = $query->paginate(24);

        // Get distinct sources for filter dropdown
        $sources = Media::select('source')->distinct()->orderBy('source')->pluck('source');

        return view('admin.media.index', compact('media', 'sources'));
    }

    /**
     * Upload new images to gallery.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'images'   => 'required|array|max:20',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $uploaded = [];

        foreach ($request->file('images') as $file) {
            $url = $this->fileService->upload($file, 'gallery');

            // Extract relative path from URL
            $path = str_replace(asset('storage') . '/', '', $url);
            $path = str_replace('/storage/', '', $path);

            $media = Media::create([
                'filename'    => $file->getClientOriginalName(),
                'path'        => $path,
                'url'         => $url,
                'mime_type'   => $file->getMimeType(),
                'size'        => $file->getSize(),
                'uploaded_by' => auth('admin')->id(),
                'source'      => 'gallery',
            ]);

            $uploaded[] = $media;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => count($uploaded) . ' gambar berhasil diupload.',
                'media'   => $uploaded,
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', count($uploaded) . ' gambar berhasil diupload.');
    }

    /**
     * Delete a media item.
     */
    public function destroy(Media $media): RedirectResponse|JsonResponse
    {
        $this->fileService->delete($media->url);
        $media->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Gambar berhasil dihapus.']);
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * JSON API endpoint for gallery picker modal.
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Media::latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('source')) {
            $query->fromSource($request->source);
        }

        $media = $query->paginate(24);

        return response()->json($media);
    }
}
