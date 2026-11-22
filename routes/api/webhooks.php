<?php

declare(strict_types=1);

use App\Http\Controllers\Webhooks\NombaWebhookController;
use App\Http\Controllers\Webhooks\PaystackWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(function () {
    Route::post('paystack', [PaystackWebhookController::class, 'handle'])
        ->middleware('verify-paystack-signature');

    Route::post('nomba', [NombaWebhookController::class, 'handle'])
        ->middleware('verify-nomba-signature');
});
