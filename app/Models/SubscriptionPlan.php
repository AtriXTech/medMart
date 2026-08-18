<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BillingInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_interval',
        'max_branches',
        'max_staff',
        'max_products',
        'is_active',
        'allowed_durations',
    ];

    protected $casts = [
        'billing_interval' => BillingInterval::class,
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'allowed_durations' => 'array',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}