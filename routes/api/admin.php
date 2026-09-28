<?php

declare(strict_types=1);

// use App\Http\Controllers\Api\V1\Admin\AuthController;

// use App\Http\Controllers\Api\V1\Admin\AuthController;


use App\Http\Controllers\Api\V1\Admin\BankController;
use App\Http\Controllers\Api\V1\Admin\SettlementAccountController;
// use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

   

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    

        // -------- Banks --------
        Route::prefix('banks')->group(function () {
            Route::get('/', [BankController::class, 'index']);
            Route::post('/', [BankController::class, 'store']);
            Route::patch('{bank}', [BankController::class, 'update']);
            Route::delete('{bank}', [BankController::class, 'destroy']);
        });

        // -------- Settlement Accounts --------
        Route::prefix('settlement-accounts')->group(function () {
            Route::get('/', [SettlementAccountController::class, 'index']);
            Route::get('pending', [SettlementAccountController::class, 'pending']);
            Route::patch('{account}/approve', [SettlementAccountController::class, 'approve']);
            Route::patch('{account}/reject', [SettlementAccountController::class, 'reject']);
        });

        // -------- Next pages go here, same pattern --------
        // Route::prefix('pharmacies')->group(function () { ... });
        // Route::prefix('customers')->group(function () { ... });
        // Route::prefix('orders')->group(function () { ... });
        // etc.

    });
});