<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\BankController;
use App\Http\Controllers\Api\V1\Admin\SettlementAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::prefix('banks')->group(function () {
        Route::get('/', [BankController::class, 'index']);
        Route::post('/', [BankController::class, 'store']);
        Route::patch('{bank}', [BankController::class, 'update']);
        Route::delete('{bank}', [BankController::class, 'destroy']);
    });

    Route::prefix('settlement-accounts')->group(function () {
        Route::get('/', [SettlementAccountController::class, 'index']);
        Route::get('pending', [SettlementAccountController::class, 'pending']);
        Route::patch('{account}/approve', [SettlementAccountController::class, 'approve']);
        Route::patch('{account}/reject', [SettlementAccountController::class, 'reject']);
    });
});