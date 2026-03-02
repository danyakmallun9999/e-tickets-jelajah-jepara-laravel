<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{
    public function edit()
    {
        $setting = ProfileSetting::first();
        if (!$setting) {
            $setting = new ProfileSetting([
                'label_id' => 'Profil Wilayah',
                'label_en' => 'Regional Profile',
                'title_id' => 'Mutiara Semenanjung.',
                'title_en' => 'The Pearl of The Peninsula.',
                'description_id' => 'Kabupaten Jepara, permata di ujung utara Jawa Tengah. Garis pantai membentang 83 km, menyatukan budaya ukir kelas dunia dengan keindahan alam tropis.',
                'description_en' => 'Jepara Regency, the jewel of Central Java\'s northern tip. An 83 km coastline uniting world-class carving culture with tropical natural beauty.',
                'stat_count' => '150',
                'stat_label_id' => 'Destinasi',
                'stat_label_en' => 'Destinations',
                'pillar_nature_title_id' => 'Alam',
                'pillar_nature_title_en' => 'Nature',
                'pillar_nature_desc_id' => 'Surga Tropis',
                'pillar_nature_desc_en' => 'Tropical Paradise',
                'pillar_heritage_title_id' => 'Sejarah',
                'pillar_heritage_title_en' => 'Heritage',
                'pillar_heritage_desc_id' => 'Bumi Kartini',
                'pillar_heritage_desc_en' => 'Land of Kartini',
                'pillar_arts_title_id' => 'Seni',
                'pillar_arts_title_en' => 'Arts',
                'pillar_arts_desc_id' => 'Ukir Dunia',
                'pillar_arts_desc_en' => 'World Carving',
            ]);
        }
        return view('admin.profile-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'label_id' => 'nullable|string|max:255',
            'label_en' => 'nullable|string|max:255',
            'title_id' => 'nullable|string',
            'title_en' => 'nullable|string',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'stat_count' => 'nullable|string|max:255',
            'stat_label_id' => 'nullable|string|max:255',
            'stat_label_en' => 'nullable|string|max:255',
            'pillar_nature_title_id' => 'nullable|string|max:255',
            'pillar_nature_title_en' => 'nullable|string|max:255',
            'pillar_nature_desc_id' => 'nullable|string',
            'pillar_nature_desc_en' => 'nullable|string',
            'pillar_heritage_title_id' => 'nullable|string|max:255',
            'pillar_heritage_title_en' => 'nullable|string|max:255',
            'pillar_heritage_desc_id' => 'nullable|string',
            'pillar_heritage_desc_en' => 'nullable|string',
            'pillar_arts_title_id' => 'nullable|string|max:255',
            'pillar_arts_title_en' => 'nullable|string|max:255',
            'pillar_arts_desc_id' => 'nullable|string',
            'pillar_arts_desc_en' => 'nullable|string',
            'image_main' => 'nullable|image|max:5120',
            'image_secondary' => 'nullable|image|max:5120',
        ]);

        $setting = ProfileSetting::first() ?? new ProfileSetting();
        $setting->fill($validated);

        if ($request->hasFile('image_main')) {
            if ($setting->image_main) {
                Storage::disk('public')->delete($setting->image_main);
            }
            $setting->image_main = $request->file('image_main')->store('profile', 'public');
        } elseif ($request->filled('image_main_gallery_url')) {
            $url = $request->input('image_main_gallery_url');
            $baseUrl = rtrim(Storage::url(''), '/');
            $path = ltrim(str_replace($baseUrl, '', $url), '/');
            if (!empty($path)) {
                $setting->image_main = $path;
            }
        }

        if ($request->hasFile('image_secondary')) {
            if ($setting->image_secondary) {
                Storage::disk('public')->delete($setting->image_secondary);
            }
            $setting->image_secondary = $request->file('image_secondary')->store('profile', 'public');
        } elseif ($request->filled('image_secondary_gallery_url')) {
            $url = $request->input('image_secondary_gallery_url');
            $baseUrl = rtrim(Storage::url(''), '/');
            $path = ltrim(str_replace($baseUrl, '', $url), '/');
            if (!empty($path)) {
                $setting->image_secondary = $path;
            }
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan Profile berhasil diperbarui.');
    }
}
