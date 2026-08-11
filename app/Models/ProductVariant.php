<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class ProductVariant extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'product_id',
        'name',
        'name_en',
        'name_de',
        'weight',
        'price',
        'sku',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
}
