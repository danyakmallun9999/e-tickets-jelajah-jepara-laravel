<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Culture extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'image',
        'description',
        'description_en',
        'content',
        'content_en',
        'category',
        'location',
        'time',
        'youtube_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($culture) {
            if (!$culture->slug) {
                $slug = Str::slug($culture->name);
                // Check if slug already exists
                if (static::where('slug', $slug)->exists()) {
                    $slug = $slug . '-' . strtolower(Str::random(5));
                }
                $culture->slug = $slug;
            }
        });

        static::updating(function ($culture) {
            if ($culture->isDirty('name') && !$culture->isDirty('slug')) {
                $slug = Str::slug($culture->name);
                // Check if slug already exists and it's not the current model's slug
                if (static::where('slug', $slug)->where('id', '!=', $culture->id)->exists()) {
                    $slug = $slug . '-' . strtolower(Str::random(5));
                }
                $culture->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function images()
    {
        return $this->hasMany(CultureImage::class);
    }

    public function locations()
    {
        return $this->hasMany(CultureLocation::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (Str::startsWith($this->image, 'http')) {
            return $this->image;
        }

        if (file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        return asset($this->image);
    }
}
