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
        'slug',
        'description',
        'order',
        'is_active',
        'meta_title',
        'meta_description',
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
}
