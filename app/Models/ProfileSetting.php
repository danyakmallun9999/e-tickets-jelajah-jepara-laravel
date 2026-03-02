<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSetting extends Model
{
    protected $fillable = [
        'label_id',
        'label_en',
        'title_id',
        'title_en',
        'description_id',
        'description_en',
        'image_main',
        'image_secondary',
        'stat_count',
        'stat_label_id',
        'stat_label_en',
        'pillar_nature_title_id',
        'pillar_nature_title_en',
        'pillar_nature_desc_id',
        'pillar_nature_desc_en',
        'pillar_heritage_title_id',
        'pillar_heritage_title_en',
        'pillar_heritage_desc_id',
        'pillar_heritage_desc_en',
        'pillar_arts_title_id',
        'pillar_arts_title_en',
        'pillar_arts_desc_id',
        'pillar_arts_desc_en',
    ];

    public function getTranslatedAttribute($attribute)
    {
        $locale = app()->getLocale();
        $localizedField = $attribute . '_' . $locale;
        
        return $this->{$localizedField} ?? $this->{$attribute . '_id'};
    }
}
