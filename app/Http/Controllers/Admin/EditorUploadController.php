<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditorUploadController extends Controller
{
    /**
     * Handle image upload from Editor.js.
     * 
     * Editor.js expects response format: { success: 1, file: { url: '...' } }
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            // Handle URL-based image
            if ($request->query('type') === 'url') {
                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                    'url' => 'required|url'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => 0,
                        'message' => $validator->errors()->first()
                    ]);
                }
                
                return response()->json([
                    'success' => 1,
                    'file' => [
                        'url' => $request->input('url'),
                    ],
                ]);
            }

            // Handle file upload
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            ], [
                'image.required' => 'File gambar tidak ditemukan atau file terlalu besar (max 4MB).',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus JPG, PNG, WEBP, atau GIF.',
                'image.max' => 'Ukuran gambar maksimal 4MB.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 0,
                    'message' => $validator->errors()->first()
                ]);
            }

            $file = $request->file('image');
            $path = $file->store('editor-uploads/' . date('Y/m'), 'public');

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => asset('storage/' . $path),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('EditorJS Upload Error: ' . $e->getMessage());
            return response()->json([
                'success' => 0,
                'message' => 'Terjadi kesalahan sistem saat mengunggah gambar.'
            ]);
        }
    }
}
