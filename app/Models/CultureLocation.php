<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultureLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'culture_id',
        'name',
        'address',
        'google_maps_url',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'is_recommended',
    ];

    public function culture()
    {
        return $this->belongsTo(Culture::class);
    }
}
