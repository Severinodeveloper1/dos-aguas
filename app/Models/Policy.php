<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
