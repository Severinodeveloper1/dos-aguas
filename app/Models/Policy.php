<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'title_de',
        'slug',
        'content',
        'content_en',
        'content_de',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Accesores multilingües (ES, EN, DE)
     */
    public function getDisplayTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }

        if ($locale === 'de' && !empty($this->title_de)) {
            return $this->title_de;
        }

        return $this->title;
    }

    public function getDisplayContentAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' && !empty($this->content_en)) {
            return $this->content_en;
        }

        if ($locale === 'de' && !empty($this->content_de)) {
            return $this->content_de;
        }

        return $this->content;
    }
}
