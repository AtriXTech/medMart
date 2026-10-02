<?php

declare(strict_types=1);

namespace App\Settlement\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action',
        'pharmacy_id',
        'subject_type',
        'subject_id',
        'actor_id',
        'ip_address',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $log): void {
            if ($log->created_at === null) {
                $log->created_at = now();
            }
        });
    }
}
