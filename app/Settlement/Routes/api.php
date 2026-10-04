<?php

declare(strict_types=1);

use App\Settlement\Http\Controllers\Admin\AccountReviewController;
use App\Settlement\Http\Controllers\Admin\ControlController;
use App\Settlement\Http\Controllers\Admin\GatewayController;
use App\Settlement\Http\Controllers\Admin\JobController;
use App\Settlement\Http\Controllers\Admin\LedgerAdminController;
use App\Settlement\Http\Controllers\Admin\PaymentAdminController;
use App\Settlement\Http\Controllers\Admin\PharmacyPayoutController;
use App\Settlement\Http\Controllers\Admin\SettlementAdminController;
use App\Settlement\Http\Controllers\Staff\BalanceController;
use App\Settlement\Http\Controllers\Staff\LedgerController;
use App\Settlement\Http\Controllers\Staff\SettlementAccountController;
use App\Settlement\Http\Controllers\Staff\SettlementController;
use App\Settlement\Http\Controllers\Webhook\PaystackTransferWebhookController;
use App\Settlement\Http\Middleware\EnsureSuperAdmin;
use App\Settlement\Http\Middleware\VerifyPaystackSignature;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    Route::post('webhooks/paystack/transfers', PaystackTransferWebhookController::class)
        ->middleware(VerifyPaystackSignature::class);

    Route::middleware([
        'api',
        'auth:sanctum',
        'staff',
        'permission:' . config('settlement.permissions.manage'),
    ])->prefix('staff')->group(function () {
        Route::get('settlement-account', [SettlementAccountController::class, 'show']);
        Route::post('settlement-account', [SettlementAccountController::class, 'store'])->middleware('throttle:6,1');

        Route::get('settlements', [SettlementController::class, 'index']);
        Route::get('settlements/{settlement}', [SettlementController::class, 'show'])->whereNumber('settlement');

        Route::get('settlement-balance', BalanceController::class);
        Route::get('settlement-ledger', [LedgerController::class, 'index']);
    });

    Route::middleware(['api', 'auth:sanctum', EnsureSuperAdmin::class])
        ->prefix('admin/settlement')
        ->group(function () {
            Route::get('accounts', [AccountReviewController::class, 'index']);
            Route::post('accounts/{account}/approve', [AccountReviewController::class, 'approve'])->whereNumber('account');
            Route::post('accounts/{account}/reject', [AccountReviewController::class, 'reject'])->whereNumber('account');

            Route::get('settlements', [SettlementAdminController::class, 'index']);
            Route::get('settlements/{settlement}', [SettlementAdminController::class, 'show'])->whereNumber('settlement');
            Route::post('settlements/{settlement}/retry', [SettlementAdminController::class, 'retry'])->whereNumber('settlement');
            Route::post('settlements/{settlement}/hold', [SettlementAdminController::class, 'hold'])->whereNumber('settlement');
            Route::post('settlements/{settlement}/release', [SettlementAdminController::class, 'release'])->whereNumber('settlement');
            Route::post('settlements/{settlement}/cancel', [SettlementAdminController::class, 'cancel'])->whereNumber('settlement');
            Route::post('settlements/{settlement}/replay', [GatewayController::class, 'replay'])->whereNumber('settlement');
            Route::post('run', [SettlementAdminController::class, 'run']);

            Route::get('gateway', [GatewayController::class, 'status']);

            Route::get('controls', [ControlController::class, 'show']);
            Route::put('controls', [ControlController::class, 'update']);
            Route::post('controls/pause', [ControlController::class, 'pause']);
            Route::post('controls/resume', [ControlController::class, 'resume']);

            Route::get('pharmacies/{pharmacy}', [PharmacyPayoutController::class, 'show'])->whereNumber('pharmacy');
            Route::get('pharmacies/{pharmacy}/ledger', [LedgerAdminController::class, 'index'])->whereNumber('pharmacy');
            Route::patch('pharmacies/{pharmacy}/payout', [PharmacyPayoutController::class, 'update'])->whereNumber('pharmacy');

            Route::get('payments', [PaymentAdminController::class, 'index']);

            Route::post('payments/{payment}/refund', [PaymentAdminController::class, 'refund'])->whereNumber('payment');
            Route::post('payments/{payment}/hold', [PaymentAdminController::class, 'hold'])->whereNumber('payment');
            Route::post('payments/{payment}/release', [PaymentAdminController::class, 'release'])->whereNumber('payment');

            Route::post('jobs/{job}', JobController::class)->where('job', '[a-z-]+');
        });
});
