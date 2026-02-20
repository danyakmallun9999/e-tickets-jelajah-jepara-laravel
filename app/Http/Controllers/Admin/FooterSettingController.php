<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterSetting;

class FooterSettingController extends Controller
{
    public function edit()
    {
        $setting = FooterSetting::first();
        if (!$setting) {
            $setting = new FooterSetting([
                'about_id' => 'Jelajah Jepara adalah portal resmi pariwisata Kabupaten Jepara. Temukan berbagai destinasi menarik, event seru, dan beragam kebudayaan serta kuliner khas Jepara.',
                'about_en' => 'Jelajah Jepara is the official tourism portal of Jepara Regency. Discover various interesting destinations, exciting events, and various Jepara cultures and culinary delights.',
                'address' => 'Jl. Abdul Rahman Hakim No. 51, Jepara',
                'phone' => '(0291) 591219',
                'email' => 'disparbud@jepara.go.id',
                'facebook_link' => 'https://www.facebook.com/disparbudjepara',
                'instagram_link' => 'https://www.instagram.com/disparbudjepara',
                'youtube_link' => '',
                'twitter_link' => '',
            ]);
        }
        return view('admin.footer-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_id' => 'nullable|string',
            'about_en' => 'nullable|string',
            'facebook_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'youtube_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $setting = FooterSetting::first() ?? new FooterSetting();
        $setting->fill($validated);
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan Footer berhasil diperbarui.');
    }
}
