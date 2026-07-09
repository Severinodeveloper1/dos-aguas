<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'claim_code',
        'document_type',
        'document_number',
        'full_name',
        'email',
        'phone',
        'address',
        'is_minor',
        'representative_name',
        'representative_document_type',
        'representative_document_number',
        'type',
        'claimed_amount',
        'product_service_description',
        'claim_details',
        'consumer_request',
        'status',
        'resolution_response',
        'resolved_at',
    ];

    protected $casts = [
        'is_minor' => 'boolean',
        'claimed_amount' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];
}
