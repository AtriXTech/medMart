<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use BelongsToPharmacy, HasFactory, SoftDeletes;

    protected $fillable = [
        'pharmacy_id',
        'name',
        'contact_name',
        'phone',
        'email',
        'address',
    ];

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
