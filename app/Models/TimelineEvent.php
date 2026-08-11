<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    protected $fillable = [
        'year',
        'title',
        'title_en',
        'title_de',
        'description',
        'description_en',
        'description_de',
        'image_path',
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
}
