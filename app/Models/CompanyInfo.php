<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class CompanyInfo extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'company_infos';

    protected $fillable = [
        'name',
        'logo_path',
        'phone',
        'whatsapp_phone',
        'email',
        'address',
        'maps_iframe',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'youtube_url',
        'about_history',
        'about_mission',
        'about_vision',
        'about_values',
        'brochure_path',
        'contact_email_receiver',
        'gallery_photos',
        'mission', // Keep old fields for backward compatibility
        'vision',
        'short_history',
    ];

    protected $casts = [
        'gallery_photos' => 'array',
    ];
}

