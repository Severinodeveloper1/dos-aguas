<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'title_de',
        'description',
        'description_en',
        'description_de',
        'country',
        'country_en',
        'country_de',
        'date',
        'product_image',
        'medal_image',
        'certificate_image',
    ];

    protected $casts = [
        'date' => 'date',
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

    public function getDisplayDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        if ($locale === 'de' && !empty($this->description_de)) {
            return $this->description_de;
        }
        return $this->description ?? '';
    }

    public function getDisplayCountryAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->country_en)) {
            return $this->country_en;
        }
        if ($locale === 'de' && !empty($this->country_de)) {
            return $this->country_de;
        }
        return $this->country ?? '';
    }
}
