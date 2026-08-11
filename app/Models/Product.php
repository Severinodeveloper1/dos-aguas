<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Product extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'category_id',
        'name',
        'name_en',
        'name_de',
        'slug',
        'description',
        'description_en',
        'description_de',
        'images',
        'tasting_notes',
        'tasting_notes_en',
        'tasting_notes_de',
        'nutritional_values',
        'nutritional_values_en',
        'nutritional_values_de',
        'natural_benefits',
        'natural_benefits_en',
        'natural_benefits_de',
        'is_active',
        'meta_title',
        'meta_title_en',
        'meta_title_de',
        'meta_description',
        'meta_description_en',
        'meta_description_de',
    ];

    protected $casts = [
        'images' => 'array',
        'nutritional_values' => 'array',
        'nutritional_values_en' => 'array',
        'nutritional_values_de' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // ── Multilingual Accessors ──────────────────────────────────────────────────

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

    public function getDisplayTastingNotesAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->tasting_notes_en)) {
            return $this->tasting_notes_en;
        }
        if ($locale === 'de' && !empty($this->tasting_notes_de)) {
            return $this->tasting_notes_de;
        }
        return $this->tasting_notes ?? '';
    }

    public function getDisplayNaturalBenefitsAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->natural_benefits_en)) {
            return $this->natural_benefits_en;
        }
        if ($locale === 'de' && !empty($this->natural_benefits_de)) {
            return $this->natural_benefits_de;
        }
        return $this->natural_benefits ?? '';
    }

    public function getDisplayNutritionalValuesAttribute(): array
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->nutritional_values_en)) {
            return $this->nutritional_values_en;
        }
        if ($locale === 'de' && !empty($this->nutritional_values_de)) {
            return $this->nutritional_values_de;
        }
        return $this->nutritional_values ?? [];
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

    // ── Helper Verification Methods ─────────────────────────────────────────────

    public function hasTastingNotes(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->display_tasting_notes)))) !== '';
    }

    public function getCleanTastingNotesAttribute(): string
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->display_tasting_notes))));
    }

    public function hasDescription(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->display_description)))) !== '';
    }

    public function getCleanDescriptionAttribute(): string
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->display_description))));
    }

    public function hasNaturalBenefits(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->display_natural_benefits)))) !== '';
    }
}
