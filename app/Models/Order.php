<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DeliveryStatus;
use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Traits\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use BelongsToPharmacy, HasFactory, SoftDeletes;

    protected $fillable = [
        'pharmacy_id',
        'customer_id',
        'status',
        'subtotal',
        'total',
        'fulfillment_type',
        'delivery_address',
        'delivery_status',
        'ready_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'fulfillment_type' => FulfillmentType::class,
        'delivery_status' => DeliveryStatus::class,
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'ready_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
