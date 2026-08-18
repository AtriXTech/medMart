<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PrescriptionStatus;
use App\Traits\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use BelongsToPharmacy, HasFactory, SoftDeletes;

    protected $fillable = [
        'pharmacy_id',
        'customer_id',
        'file_path',
        'original_filename',
        'status',
        'reviewed_by_id',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => PrescriptionStatus::class,
        'reviewed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }
}
