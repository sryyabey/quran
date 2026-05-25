<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VerseTranslationsDatasetController extends Controller
{
    public function download(): BinaryFileResponse|JsonResponse
    {
        $path = storage_path('data/verse_translations.dataset.zip');

        if (! is_file($path)) {
            return response()->json([
                'message' => 'Verse translations dataset not found.',
            ], 404);
        }

        return response()->download($path, 'verse_translations.dataset.zip', [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
