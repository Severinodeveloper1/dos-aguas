<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class CompanyInfo extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'company_infos';

    protected $fillable = [
        'mission',
        'vision',
        'short_history',
    ];
}

