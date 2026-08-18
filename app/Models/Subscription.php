<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'subscription_plan_id',
        'status',
        'paystack_subscription_code',
        'paystack_customer_code',
        'current_period_starts_at',
        'current_period_ends_at',
        'cancelled_at',
        'renewal_reminder_sent_at',
    ];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'current_period_starts_at' => 'datetime',
        'current_period_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'renewal_reminder_sent_at' => 'datetime',
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
