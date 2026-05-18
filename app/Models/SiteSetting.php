<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo',
        'favicon',
        'site_name',
        'main_banner',
        'sponsor_banner',
        'phone_primary',
        'phone_secondary',
        'email',
        'address',
        'fb_link',
        'youtube_link',
        'whatsapp_link'
    ];

    protected $casts = [
        'sponsor_banner' => 'array',
    ];
}
