<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Customer\AuthController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\CheckoutController;
use App\Http\Controllers\Api\V1\Customer\EmailVerificationController;
use App\Http\Controllers\Api\V1\Customer\NotificationController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\Customer\PaymentController;
use App\Http\Controllers\Api\V1\Customer\PharmacyLinkController;
use App\Http\Controllers\Api\V1\Customer\PrescriptionController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\ProfileController;

Route::prefix('customer')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('verify-email', [EmailVerificationController::class, 'verify']);
    Route::post('resend-verification', [EmailVerificationController::class, 'resend']);

    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::get('profile', [ProfileController::class, 'show']);
        Route::patch('profile', [ProfileController::class, 'update']);
        Route::post('profile/password', [ProfileController::class, 'changePassword']);

        Route::prefix('pharmacies')->group(function () {
            Route::get('/', [PharmacyLinkController::class, 'index']);
            Route::post('join', [PharmacyLinkController::class, 'join']);
            Route::patch('switch', [PharmacyLinkController::class, 'switch']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('{order}', [OrderController::class, 'show']);
            Route::post('{order}/pay', [PaymentController::class, 'initiate']);
            Route::post('{order}/cancel', [OrderController::class, 'cancel']);
        });

        Route::post('payments/verify', [PaymentController::class, 'verify']);

        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::patch('read-all', [NotificationController::class, 'markAllAsRead']);
            Route::patch('{notification}/read', [NotificationController::class, 'markAsRead']);
        });

        Route::middleware(['has-active-pharmacy'])->group(function () {
            Route::prefix('products')->group(function () {
                Route::get('/', [ProductController::class, 'index']);
                Route::get('{product}', [ProductController::class, 'show']);
            });

            Route::prefix('cart')->group(function () {
                Route::get('/', [CartController::class, 'show']);
                Route::post('items', [CartController::class, 'addItem']);
                Route::patch('items/{cartItem}', [CartController::class, 'updateItem']);
                Route::delete('items/{cartItem}', [CartController::class, 'removeItem']);
            });

            Route::post('checkout', [CheckoutController::class, 'store']);

            Route::prefix('prescriptions')->group(function () {
                Route::get('/', [PrescriptionController::class, 'index']);
                Route::post('/', [PrescriptionController::class, 'store']);
            });
        });
    });
});
