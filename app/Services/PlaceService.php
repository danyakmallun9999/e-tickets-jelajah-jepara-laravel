<?php

namespace App\Services;

use App\Models\Place;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PlaceService
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Store an image for a place.
     */
    public function uploadImage(UploadedFile $file, string $directory = 'places'): string
    {
        return $this->fileService->upload($file, $directory);
    }

    /**
     * Delete an image from storage.
     */
    public function deleteImage(?string $path): void
    {
        $this->fileService->delete($path);
    }

    /**
     * Parse facilities string into array.
     */
    public function parseFacilities(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }

    /**
     * Parse rides string into array.
     */
    public function parseRides(?string $text): array
    {
        if (! $text) {
            return [];
        }
        $lines = explode("\n", $text);
        $rides = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $parts = explode('-', $line);

            if (count($parts) > 1) {
                // Assume last part is price
                $price = trim(array_pop($parts));
                $name = trim(implode('-', $parts));

                $rides[] = [
                    'name' => $name,
                    'price' => $price,
                ];
            } else {
                $rides[] = [
                    'name' => $line,
                    'price' => null,
                ];
            }
        }

        return $rides;
    }

    /**
     * Generate a unique slug for a place.
     */
    public function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Place::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$count;
            $count++;

            // Re-check with new slug
            $query = Place::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
