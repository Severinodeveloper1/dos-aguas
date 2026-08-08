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
        'slug',
        'description',
        'images',
        'tasting_notes',
        'nutritional_values',
        'natural_benefits',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'images' => 'array',
        'nutritional_values' => 'array',
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

    public function hasTastingNotes(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->tasting_notes ?? '')))) !== '';
    }

    public function getCleanTastingNotesAttribute(): string
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->tasting_notes ?? ''))));
    }

    public function hasDescription(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->description ?? '')))) !== '';
    }

    public function getCleanDescriptionAttribute(): string
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->description ?? ''))));
    }

    public function hasNaturalBenefits(): bool
    {
        return trim(strip_tags(html_entity_decode(str_replace('&nbsp;', ' ', $this->natural_benefits ?? '')))) !== '';
    }
}

