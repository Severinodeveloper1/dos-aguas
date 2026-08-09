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
        'hacienda_video_url',
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

    public function getHaciendaYoutubeIdAttribute(): ?string
    {
        if (empty($this->hacienda_video_url)) {
            return null;
        }

        $url = trim($this->hacienda_video_url);

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function getHaciendaYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->hacienda_youtube_id;
        return $id ? "https://www.youtube-nocookie.com/embed/{$id}?rel=0" : null;
    }
}

