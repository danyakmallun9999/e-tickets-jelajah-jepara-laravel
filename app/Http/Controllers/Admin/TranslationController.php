<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        try {
            $tr = new GoogleTranslate();
            $tr->setSource($request->source ?? 'id'); 
            $tr->setTarget($request->target);
            
            $text = $request->text;
            
            // Handle HTML content (basic approach to preserve tags if needed, 
            // but Google Translate PHP handles plain text best. 
            // For rich text editors, we might need to send raw HTML which Google Translate supports)
            $translatedText = $tr->translate($text);

            return response()->json([
                'success' => true,
                'translation' => $translatedText
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
