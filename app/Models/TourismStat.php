<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'visitors',
    ];
}
