<?php

declare(strict_types=1);

namespace App\Payments\Models;

use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'json',
    ];
}
