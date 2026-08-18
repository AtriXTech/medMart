<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_token_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pharmacyLinks(): HasMany
    {
        return $this->hasMany(CustomerPharmacyLink::class);
    }

    public function pharmacies(): BelongsToMany
    {
        return $this->belongsToMany(Pharmacy::class, 'customer_pharmacy_links')
            ->withPivot('id', 'is_active')
            ->withTimestamps();
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function activePharmacy(): ?Pharmacy
    {
        $link = $this->pharmacyLinks()->where('is_active', true)->with('pharmacy')->first();

        return $link?->pharmacy;
    }
}
