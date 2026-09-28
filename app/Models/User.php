<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'pharmacy_id',
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'last_login_at',
        'staff_role_id',
        'is_super_admin' 


    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'role' => StaffRole::class,
    ];

    public function canAccessPanel(Panel $panel): bool
{
    return $this->is_super_admin === true;
}

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function staffRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'staff_role_id');
    }
}
