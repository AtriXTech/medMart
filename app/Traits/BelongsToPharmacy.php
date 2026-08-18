<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToPharmacy
{
    protected static function bootBelongsToPharmacy(): void
    {
        static::addGlobalScope('pharmacy', function (Builder $builder) {
            $user = Auth::guard('sanctum')->user();

            if ($user instanceof User) {
                $builder->where($builder->getModel()->getTable() . '.pharmacy_id', $user->pharmacy_id);
            }
        });

        static::creating(function ($model) {
            if (empty($model->pharmacy_id)) {
                $user = Auth::guard('sanctum')->user();

                if ($user instanceof User) {
                    $model->pharmacy_id = $user->pharmacy_id;
                }
            }
        });
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
