<?php

namespace App\Models;

use App\Models\Place;
use Illuminate\Database\Eloquent\Model;

class PlaceImage extends Model
{
    protected $fillable = ['place_id', 'image_path'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
