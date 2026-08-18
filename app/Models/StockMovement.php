<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StockMovementType;
use App\Traits\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use BelongsToPharmacy, HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'product_id',
        'batch_id',
        'staff_id',
        'type',
        'quantity',
        'reason',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'type' => StockMovementType::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
