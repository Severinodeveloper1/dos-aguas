<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Category extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'name',
        'name_en',
        'name_de',
        'slug',
        'description',
        'description_en',
        'description_de',
        'order',
        'is_active',
        'meta_title',
        'meta_title_en',
        'meta_title_de',
        'meta_description',
        'meta_description_en',
        'meta_description_de',
        'photo_path',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        if ($locale === 'de' && !empty($this->name_de)) {
            return $this->name_de;
        }
        return $this->name ?? '';
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

    public function getDisplayMetaTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->meta_title_en)) {
            return $this->meta_title_en;
        }
        if ($locale === 'de' && !empty($this->meta_title_de)) {
            return $this->meta_title_de;
        }
        return $this->meta_title ?? '';
    }

    public function getDisplayMetaDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->meta_description_en)) {
            return $this->meta_description_en;
        }
        if ($locale === 'de' && !empty($this->meta_description_de)) {
            return $this->meta_description_de;
        }
        return $this->meta_description ?? '';
    }
}
