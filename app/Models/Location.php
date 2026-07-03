<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Location extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'name',
        'type',
        'address',
        'map_frames',
        'phone',
        'hours',
        'is_active',
    ];

    protected $casts = [
        'map_frames' => 'array',
        'is_active' => 'boolean',
    ];
}

