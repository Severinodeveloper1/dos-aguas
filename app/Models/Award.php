<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'title',
        'description',
        'country',
        'date',
        'product_image',
        'medal_image',
        'certificate_image',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
