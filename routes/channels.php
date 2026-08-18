<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('pharmacy.{pharmacyId}', function ($user, $pharmacyId) {
    $pharmacyId = (int) $pharmacyId;

    if ($user instanceof Customer) {
        return $user->pharmacyLinks()
            ->where('pharmacy_id', $pharmacyId)
            ->where('is_active', true)
            ->exists();
    }

    if ($user instanceof User) {
        return $user->pharmacy_id === $pharmacyId;
    }

    return false;
});

Broadcast::channel('customer.{customerId}', function ($user, $customerId) {
    $customerId = (int) $customerId;

    return $user instanceof Customer && $user->id === $customerId;
});
