<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSettingController extends Controller
{
    public function edit()
    {
        $setting = \App\Models\HeroSetting::first();
        if (!$setting) {
            $setting = new \App\Models\HeroSetting([
               'type' => 'map',
               'badge_id' => 'Portal Resmi Pariwisata',
               'badge_en' => 'Official Tourism Portal',
               'title_id' => "Jelajah Jepara.\nUkir Ceritamu Di Sini",
               'title_en' => "Explore Jepara.\nCarve Your Story Here",
               'subtitle_id' => 'Temukan pesona pantai tropis, kekayaan sejarah, dan mahakarya ukiran kayu kelas dunia.',
               'subtitle_en' => 'Discover tropical beaches, rich history, and world-class wood carving masterpieces.',
               'button_text_id' => 'Mulai Jelajah',
               'button_text_en' => 'Start Exploring',
               'button_link' => '#explore'
            ]);
        }
        return view('admin.hero-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:map,video,image',
            'title_id' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle_id' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'badge_id' => 'nullable|string|max:255',
            'badge_en' => 'nullable|string|max:255',
            'button_text_id' => 'nullable|string|max:255',
            'button_text_en' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'video_file' => 'nullable|mimes:mp4,webm,ogg|max:50000', // max 50MB
            'mobile_video_file' => 'nullable|mimes:mp4,webm,ogg|max:50000',
            'image_files.*' => 'nullable|image|max:10240', // max 10MB per image
            'mobile_image_files.*' => 'nullable|image|max:10240',
            'image_files_gallery_url' => 'nullable|array',
            'image_files_gallery_url.*' => 'nullable|string',
            'mobile_image_files_gallery_url' => 'nullable|array',
            'mobile_image_files_gallery_url.*' => 'nullable|string',
            'remove_media' => 'nullable|boolean',
            'existing_media' => 'nullable|array',
        ]);

        $setting = \App\Models\HeroSetting::first() ?? new \App\Models\HeroSetting();
        $setting->type = $validated['type'];
        $setting->title_id = $validated['title_id'] ?? null;
        $setting->title_en = $validated['title_en'] ?? null;
        $setting->subtitle_id = $validated['subtitle_id'] ?? null;
        $setting->subtitle_en = $validated['subtitle_en'] ?? null;
        $setting->badge_id = $validated['badge_id'] ?? null;
        $setting->badge_en = $validated['badge_en'] ?? null;
        $setting->button_text_id = $validated['button_text_id'] ?? null;
        $setting->button_text_en = $validated['button_text_en'] ?? null;
        $setting->button_link = $validated['button_link'] ?? null;

        $mediaPaths = $setting->media_paths ?? [];
        $mobileMediaPaths = $setting->mobile_media_paths ?? [];
        $newGalleryPaths = [];
        $newMobileGalleryPaths = [];
        
        // Handle desktop gallery selections
        if ($validated['type'] === 'image' && $request->has('image_files_gallery_url')) {
            foreach($request->input('image_files_gallery_url') as $url) {
                $baseUrl = rtrim(Storage::url(''), '/');
                $path = ltrim(str_replace($baseUrl, '', $url), '/');
                if (!empty($path)) $newGalleryPaths[] = $path;
            }
        }
        
        // Handle mobile gallery selections
        if ($validated['type'] === 'image' && $request->has('mobile_image_files_gallery_url')) {
             foreach($request->input('mobile_image_files_gallery_url') as $url) {
                $baseUrl = rtrim(Storage::url(''), '/');
                $path = ltrim(str_replace($baseUrl, '', $url), '/');
                if (!empty($path)) $newMobileGalleryPaths[] = $path;
             }
        }

        // Handle media removal
        if ($request->boolean('remove_media') || $setting->isDirty('type')) {
             if (is_array($mediaPaths)) foreach($mediaPaths as $path) Storage::disk('public')->delete($path);
             if (is_array($mobileMediaPaths)) foreach($mobileMediaPaths as $path) Storage::disk('public')->delete($path);
             $mediaPaths = [];
             $mobileMediaPaths = [];
        } else {
             if ($validated['type'] === 'image') {
                 foreach($mediaPaths as $oldPath) if (!in_array($oldPath, $newGalleryPaths)) Storage::disk('public')->delete($oldPath);
                 $mediaPaths = $newGalleryPaths;
                 
                 foreach($mobileMediaPaths as $oldPath) if (!in_array($oldPath, $newMobileGalleryPaths)) Storage::disk('public')->delete($oldPath);
                 $mobileMediaPaths = $newMobileGalleryPaths;
             } elseif ($validated['type'] === 'video' && $request->hasFile('video_file')) { // Desktop video changed
                 foreach($mediaPaths as $oldPath) Storage::disk('public')->delete($oldPath);
                 $mediaPaths = [];
             }
             if ($validated['type'] === 'video' && $request->hasFile('mobile_video_file')) { // Mobile video changed
                 foreach($mobileMediaPaths as $oldPath) Storage::disk('public')->delete($oldPath);
                 $mobileMediaPaths = [];
             }
        }

        // Upload desktop video
        if ($validated['type'] === 'video' && $request->hasFile('video_file')) {
             $mediaPaths = [$request->file('video_file')->store('hero', 'public')];
        }
        // Upload mobile video
        if ($validated['type'] === 'video' && $request->hasFile('mobile_video_file')) {
             $mobileMediaPaths = [$request->file('mobile_video_file')->store('hero/mobile', 'public')];
        }
        
        if ($validated['type'] === 'image') {
             // Upload desktop images
             if ($request->hasFile('image_files')) {
                 foreach($request->file('image_files') as $file) $mediaPaths[] = $file->store('hero', 'public');
             }
             // Upload mobile images
             if ($request->hasFile('mobile_image_files')) {
                 foreach($request->file('mobile_image_files') as $file) $mobileMediaPaths[] = $file->store('hero/mobile', 'public');
             }
        }

        // Validation
        if ($validated['type'] !== 'map') {
            if (empty($mediaPaths)) {
                return redirect()->back()->withErrors(['media_paths' => 'Berkas media utama (Desktop) wajib diunggah untuk tipe Video/Gambar.'])->withInput();
            }
            
            // For images, ensure same count only if mobile paths are utilized
            if ($validated['type'] === 'image' && !empty($mobileMediaPaths) && count($mediaPaths) !== count($mobileMediaPaths)) {
                 return redirect()->back()->withErrors(['mobile_media_paths' => 'Karena opsional diisi, jumlah gambar Mobile ('.count($mobileMediaPaths).') harus sama persis dengan Desktop ('.count($mediaPaths).') agar slider sinkron.'])->withInput();
            }
        }

        $setting->media_paths = count($mediaPaths) > 0 ? $mediaPaths : null;
        $setting->mobile_media_paths = count($mobileMediaPaths) > 0 ? $mobileMediaPaths : null;
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan Hero berhasil diperbarui.');
    }
}
