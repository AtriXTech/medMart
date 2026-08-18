<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . '/api/staff.php';
    require __DIR__ . '/api/customer.php';
    require __DIR__ . '/api/pharmacy.php';
    require __DIR__ . '/api/admin.php';
    require __DIR__ . '/api/webhooks.php';
});
