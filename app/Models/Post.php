<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Post extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'author_id',
        'title',
        'title_en',
        'title_de',
        'slug',
        'content',
        'content_en',
        'content_de',
        'excerpt',
        'excerpt_en',
        'excerpt_de',
        'image_path',
        'published_at',
        'is_active',
        'meta_title',
        'meta_title_en',
        'meta_title_de',
        'meta_description',
        'meta_description_en',
        'meta_description_de',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

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

    public function getDisplayExcerptAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->excerpt_en)) {
            return $this->excerpt_en;
        }
        if ($locale === 'de' && !empty($this->excerpt_de)) {
            return $this->excerpt_de;
        }
        return $this->excerpt ?? '';
    }

    public function getDisplayContentAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && !empty($this->content_en)) {
            return $this->content_en;
        }
        if ($locale === 'de' && !empty($this->content_de)) {
            return $this->content_de;
        }
        return $this->content ?? '';
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
