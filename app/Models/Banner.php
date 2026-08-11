<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Banner extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'title',
        'title_en',
        'title_de',
        'subtitle',
        'subtitle_en',
        'subtitle_de',
        'button_text',
        'button_text_en',
        'button_text_de',
        'button_url',
        'media_type',
        'media_path',
        'mobile_media_type',
        'mobile_media_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getDisplayTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        if ($locale === 'de' && !empty($this->title_de)) {
            return $this->title_de;
        }
        return $this->title ?? '';
    }

    public function getDisplaySubtitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->subtitle_en)) {
            return $this->subtitle_en;
        }
        if ($locale === 'de' && !empty($this->subtitle_de)) {
            return $this->subtitle_de;
        }
        return $this->subtitle ?? '';
    }

    public function getDisplayButtonTextAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->button_text_en)) {
            return $this->button_text_en;
        }
        if ($locale === 'de' && !empty($this->button_text_de)) {
            return $this->button_text_de;
        }
        return $this->button_text ?? '';
    }
}
