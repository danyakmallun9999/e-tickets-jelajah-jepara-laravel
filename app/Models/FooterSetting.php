<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = [
        'about_id',
        'about_en',
        'facebook_link',
        'instagram_link',
        'youtube_link',
        'twitter_link',
        'address',
        'phone',
        'email',
    ];
}
