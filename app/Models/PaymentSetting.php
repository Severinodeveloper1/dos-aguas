<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class PaymentSetting extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'payment_settings';

    protected $fillable = [
        'bank_transfer_enabled',
        'bank_transfer_details',
        'cod_enabled',
        'gateway_enabled',
        'gateway_provider',
        'gateway_public_key',
        'gateway_private_key',
        'gateway_sandbox_mode',
    ];

    protected $casts = [
        'bank_transfer_enabled' => 'boolean',
        'cod_enabled' => 'boolean',
        'gateway_enabled' => 'boolean',
        'gateway_sandbox_mode' => 'boolean',
    ];
}

