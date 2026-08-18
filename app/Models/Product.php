<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BelongsToPharmacy, HasFactory, SoftDeletes;

    protected $attributes = [
        'stock_quantity' => 0,
        'is_available' => true,
    ];

    protected $fillable = [
        'pharmacy_id',
        'product_category_id',
        'name',
        'generic_name',
        'description',
        'barcode',
        'image_url',
        'requires_prescription',
        'price',
        'reorder_level',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}