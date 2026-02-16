<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationController extends Controller
{
    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'target' => 'required|string|in:en,id',
            'source' => 'nullable|string',
        ]);

        $text = $request->text;
        $source = $request->source ?? 'id';
        $target = $request->target;

        // Strategy 1: Try Google Translate first
        try {
            $tr = new GoogleTranslate();
            $tr->setSource($source);
            $tr->setTarget($target);
            $translatedText = $tr->translate($text);

            return response()->json([
                'success' => true,
                'translation' => $translatedText,
                'provider' => 'google',
            ]);
        } catch (\Exception $e) {
            Log::warning('Google Translate failed, falling back to MyMemory: ' . $e->getMessage());
        }

        // Strategy 2: Fallback to MyMemory API
        try {
            $langpair = "{$source}|{$target}";
            $response = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q' => $text,
                'langpair' => $langpair,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['responseData']['translatedText'])) {
                    return response()->json([
                        'success' => true,
                        'translation' => $data['responseData']['translatedText'],
                        'provider' => 'mymemory',
                    ]);
                }
            }

            Log::error('MyMemory API returned unexpected response: ' . $response->body());

            return response()->json([
                'success' => false,
                'message' => 'Translation failed from both providers.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('MyMemory API also failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Semua provider terjemahan gagal. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}

