<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Population extends Model
{
    protected $fillable = [
        'total_population',
        'total_families',
        'total_male',
        'total_female',
        'age_groups',
        'education_levels',
        'jobs',
        'religions',
    ];

    protected $casts = [
        'age_groups' => 'array',
        'education_levels' => 'array',
        'jobs' => 'array',
        'religions' => 'array',
    ];
}
