<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalConfig extends Model
{
    protected $table = 'global_config';

    protected $fillable = [
    'profile_title', 'profile_description', 'img_profile_1', 'img_profile_2',
    'vision', 'missions', 'video_profile', 'school_name', 'footer_description',
    'motto',
    'headline_title', 'headline_subtitle', 'headline_img_cover',
    'headline_primary_cta_text', 'headline_primary_cta_url',
    'headline_secondary_cta_text', 'headline_secondary_cta_url',
    'school_telephone', 'school_email', 'footer_ig', 'footer_yt', 'footer_fb',
    'footer_linkedin', 'created_by', 'updated_by',
];

    protected $casts = [
        'missions' => 'array',
    ];
}