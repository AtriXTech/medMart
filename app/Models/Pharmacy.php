<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PharmacyStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pharmacy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'status',
        'timezone',
        'currency',
        'is_test_account',
        'settings',
    ];

    protected $casts = [
        'status' => PharmacyStatus::class,
        'is_test_account' => 'boolean',
        'settings' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function pharmacyCodes(): HasMany
    {
        return $this->hasMany(PharmacyCode::class);
    }

    public function customerLinks(): HasMany
    {
        return $this->hasMany(CustomerPharmacyLink::class);
    }
}
