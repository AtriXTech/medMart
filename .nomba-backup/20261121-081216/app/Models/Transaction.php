<?php

namespace App\Models;

use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\SubscriptionPayment;


class Transaction extends Model
{
    protected $table = 'transactions';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public static function unifiedQuery()
    {
        $union = DB::table('payments')
            ->select([
                DB::raw("CONCAT('payment-', payments.id) as id"),
                'payments.reference',
                'payments.pharmacy_id',
                DB::raw("'Customer Order Payment' as type"),
                'payments.amount',
                'payments.status',
                'payments.paystack_reference as gateway_reference',
                'payments.created_at',
            ])
            ->unionAll(
                DB::table('subscription_payments')
                    ->select([
                        DB::raw("CONCAT('subscription-', subscription_payments.id) as id"),
                        'subscription_payments.reference',
                        'subscription_payments.pharmacy_id',
                        DB::raw("'Subscription Payment' as type"),
                        'subscription_payments.amount',
                        'subscription_payments.status',
                        DB::raw('NULL as gateway_reference'),
                        'subscription_payments.created_at',
                    ])
            );

        $query = static::query();

        $query->getQuery()->fromSub($union, 'transactions');

        return $query;
    }
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

public function getSourceIdAttribute(): int
{
    return (int) str($this->id)->afterLast('-')->toString();
}
    public function payment(): BelongsTo
    {
        return $this->belongsTo(
            Payment::class,
            'source_id',
            'id'
        );
    }

    public function subscriptionPayment(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPayment::class,
            'source_id',
            'id'
        );
    }
}
