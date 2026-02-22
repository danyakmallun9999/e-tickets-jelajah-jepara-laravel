<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SyncMediaGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all existing files in public storage to the media gallery database table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting media sync...');
        
        $disk = Storage::disk('public');
        $files = $disk->allFiles('');
        
        $syncedCount = 0;
        
        foreach ($files as $file) {
            // Check if it's an image
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                continue;
            }

            // check if file already exists in Media DB
            $existing = Media::where('path', $file)->first();
            if ($existing) {
                continue;
            }

            // Determine source based on directory
            $source = 'other';
            $parts = explode('/', $file);
            
            if (count($parts) > 1) {
                $folder = strtolower($parts[0]);
                // Map common folders to source names
                $sourceMap = [
                    'posts' => 'posts',
                    'places' => 'places',
                    'events' => 'events',
                    'hero' => 'hero',
                    'editorjs' => 'editorjs',
                    'cultures' => 'cultures',
                    'agencies' => 'agencies',
                    'users' => 'avatar'
                ];
                
                if (array_key_exists($folder, $sourceMap)) {
                    $source = $sourceMap[$folder];
                } else {
                    $source = $folder; // fallback to folder name
                }
            }

            $size = $disk->size($file);
            $lastModified = $disk->lastModified($file);

            Media::create([
                'filename' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'disk' => 'public',
                'mime_type' => ltrim($disk->mimeType($file), '.'), // Some local environments prefix with dot
                'size' => $size,
                'source' => $source,
                'created_at' => Carbon::createFromTimestamp($lastModified),
                'updated_at' => Carbon::createFromTimestamp($lastModified),
            ]);
            
            $syncedCount++;
            $this->info("Synced: {$file} (Source: {$source})");
        }

        $this->info("Media sync completed. Total newly synced files: {$syncedCount}");
    }
}
