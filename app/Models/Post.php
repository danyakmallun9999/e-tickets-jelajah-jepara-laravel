<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'type',
        'published_at',
        'is_published',
        'author',
        'image_credit',
        'title_en',
        'view_count',
        'content_en',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function getTranslatedTitleAttribute()
    {
        if (app()->getLocale() == 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return $this->title;
    }

    public function getTranslatedContentAttribute()
    {
        if (app()->getLocale() == 'en' && !empty($this->content_en)) {
            return $this->content_en;
        }
        return $this->content;
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
