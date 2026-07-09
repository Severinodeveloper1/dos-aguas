<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    protected $fillable = [
        'year',
        'title',
        'title_en',
        'description',
        'description_en',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
