<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
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
}