<?php

declare(strict_types=1);

namespace App\Models;

use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Models\LedgerEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'settlement_account_id',
        'reference',
        'amount',
        'status',
        'gateway_reference',
        'failure_reason',
        'processed_at',
        'gross_kobo',
        'fee_kobo',
        'adjustment_kobo',
        'net_kobo',
        'attempts',
        'last_attempt_at',
        'initiated_at',
        'hold_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => SettlementStatus::class,
        'pharmacy_id' => 'integer',
        'gross_kobo' => 'integer',
        'fee_kobo' => 'integer',
        'adjustment_kobo' => 'integer',
        'net_kobo' => 'integer',
        'attempts' => 'integer',
        'processed_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'initiated_at' => 'datetime',
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function settlementAccount(): BelongsTo
    {
        return $this->belongsTo(SettlementAccount::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            Payment::class,
            'settlement_payments'
        );
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
