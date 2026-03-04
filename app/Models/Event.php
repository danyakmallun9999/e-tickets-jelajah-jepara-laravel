<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'description',
        'description_en',
        'content_format',
        'location',
        'start_date',
        'end_date',
        'image',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->title).'-'.\Illuminate\Support\Str::random(5);
            }
        });

        static::updating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->title).'-'.\Illuminate\Support\Str::random(5);
            }
        });
    }

    public function getExcerptAttribute()
    {
        $content = $this->translated_description;
        
        if ($this->content_format === 'editorjs') {
            $decoded = json_decode($content, true);
            $text = '';
            if ($decoded && isset($decoded['blocks'])) {
                foreach ($decoded['blocks'] as $block) {
                    if (isset($block['data']['text'])) {
                        $text .= strip_tags($block['data']['text']) . ' ';
                    } elseif (isset($block['data']['items'])) {
                        foreach ($block['data']['items'] as $item) {
                            if (is_string($item)) {
                                $text .= strip_tags($item) . ' ';
                            } elseif (is_array($item) && isset($item['content'])) {
                                $text .= strip_tags($item['content']) . ' ';
                            }
                        }
                    }
                }
            }
            return trim($text);
        }
        
        return strip_tags($content ?? '');
    }

    /**
     * Get the user who created this event.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope a query to only include events owned by a specific user.
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function getTranslatedTitleAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return $this->title;
    }

    public function getTranslatedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->description_en)) {
            // Check if Editor.js content actually has meaningful text
            if ($this->content_format === 'editorjs') {
                $decoded = json_decode($this->description_en, true);
                if ($decoded && isset($decoded['blocks'])) {
                    $hasContent = false;
                    foreach ($decoded['blocks'] as $block) {
                        $text = trim(strip_tags($block['data']['text'] ?? $block['data']['code'] ?? ''));
                        if (!empty($text)) {
                            $hasContent = true;
                            break;
                        }
                    }
                    if (!$hasContent) {
                        return $this->description;
                    }
                }
            }
            // For legacy HTML format, check if there's actual text
            if (trim(strip_tags($this->description_en)) === '') {
                return $this->description;
            }
            return $this->description_en;
        }
        return $this->description;
    }

}
