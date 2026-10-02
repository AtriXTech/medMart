<?php

declare(strict_types=1);

namespace App\Settlement\Models;

use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Settlement\Enums\LedgerEntryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class LedgerEntry extends Model
{
    public $timestamps = false;

    protected $table = 'pharmacy_ledger_entries';

    protected $fillable = [
        'pharmacy_id',
        'type',
        'amount_kobo',
        'payment_id',
        'settlement_id',
        'order_id',
        'idempotency_key',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'type' => LedgerEntryType::class,
        'amount_kobo' => 'integer',
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $entry): void {
            if ($entry->created_at === null) {
                $entry->created_at = now();
            }
        });

        static::updating(static function (): void {
            throw new LogicException('Ledger entries are immutable.');
        });

        static::deleting(static function (): void {
            throw new LogicException('Ledger entries cannot be deleted.');
        });
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class);
    }
}
