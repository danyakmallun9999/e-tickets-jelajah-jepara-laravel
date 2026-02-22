<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'filename',
        'path',
        'url',
        'mime_type',
        'size',
        'uploaded_by',
        'source',
    ];

    /**
     * Get the admin who uploaded this media.
     */
    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Scope to filter by source.
     */
    public function scopeFromSource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope to search by filename.
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where('filename', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
