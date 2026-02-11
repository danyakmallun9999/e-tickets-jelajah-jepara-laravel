<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boundary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'area_hectares',
        'geometry',
    ];

    /**
     * Get the geometry as a decoded JSON array.
     */
    public function getGeometryAttribute($value)
    {
        return json_decode($value, true);
    }
}
