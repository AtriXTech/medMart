<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Staff\AuthController;
use App\Http\Controllers\Api\V1\Staff\BankController;
use App\Http\Controllers\Api\V1\Staff\BatchController;
use App\Http\Controllers\Api\V1\Staff\CustomerAccountController;
use App\Http\Controllers\Api\V1\Staff\CustomerController;
use App\Http\Controllers\Api\V1\Staff\DashboardController;
use App\Http\Controllers\Api\V1\Staff\OrderController;
use App\Http\Controllers\Api\V1\Staff\PharmacyCodeController;
use App\Http\Controllers\Api\V1\Staff\PharmacySettingsController;
use App\Http\Controllers\Api\V1\Staff\PrescriptionController;
use App\Http\Controllers\Api\V1\Staff\ProductCategoryController;
use App\Http\Controllers\Api\V1\Staff\ProductController;
use App\Http\Controllers\Api\V1\Staff\ProfileController;
use App\Http\Controllers\Api\V1\Staff\PurchaseOrderController;
use App\Http\Controllers\Api\V1\Staff\RoleController;
use App\Http\Controllers\Api\V1\Staff\SaleController;
use App\Http\Controllers\Api\V1\Staff\SettlementAccountController;
use App\Http\Controllers\Api\V1\Staff\StaffController;
use App\Http\Controllers\Api\V1\Staff\StockMovementController;
use App\Http\Controllers\Api\V1\Staff\SubscriptionController;
use App\Http\Controllers\Api\V1\Staff\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('staff')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware(['auth:sanctum', 'staff'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::get('profile', [ProfileController::class, 'show']);
        Route::patch('profile', [ProfileController::class, 'update']);
        Route::post('profile/password', [ProfileController::class, 'changePassword']);

        Route::get('banks', [BankController::class, 'index']);

        Route::get('subscription-plans', [SubscriptionController::class, 'plans']);

        Route::get('pharmacy-settings', [PharmacySettingsController::class, 'show'])
            ->middleware('permission:manage_pharmacy_settings');
        Route::patch('pharmacy-settings', [PharmacySettingsController::class, 'update'])
            ->middleware('permission:manage_pharmacy_settings');

        Route::prefix('subscription')->group(function () {
            Route::get('/', [SubscriptionController::class, 'current']);
            Route::post('/', [SubscriptionController::class, 'subscribe']);
            Route::post('verify-payment', [SubscriptionController::class, 'verifyPayment']);
            Route::get('payment-history', [SubscriptionController::class, 'paymentHistory']);
        });

        Route::middleware('permission:manage_staff')->group(function () {
            Route::get('staff', [StaffController::class, 'index']);
            Route::post('staff', [StaffController::class, 'store']);
            Route::patch('staff/{user}', [StaffController::class, 'update']);
            Route::patch('staff/{user}/deactivate', [StaffController::class, 'deactivate']);
        });

        Route::middleware('permission:manage_roles')->group(function () {
            Route::get('roles', [RoleController::class, 'index']);
            Route::post('roles', [RoleController::class, 'store']);
            Route::patch('roles/{role}', [RoleController::class, 'update']);
            Route::delete('roles/{role}', [RoleController::class, 'destroy']);
        });

        Route::middleware('permission:manage_settlement')->group(function () {
            Route::get('settlement-account', [SettlementAccountController::class, 'show']);
            Route::post('settlement-account', [SettlementAccountController::class, 'store']);
        });

        Route::post('customer-accounts', [CustomerAccountController::class, 'store'])
            ->middleware('permission:manage_customers');

        Route::middleware(['subscription-active'])->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])
                ->middleware('permission:view_dashboard');

            Route::prefix('product-categories')->group(function () {
                Route::get('/', [ProductCategoryController::class, 'index'])
                    ->middleware('permission:manage_categories');
                Route::post('/', [ProductCategoryController::class, 'store'])
                    ->middleware('permission:manage_categories');
                Route::patch('{category}', [ProductCategoryController::class, 'update'])
                    ->middleware('permission:manage_categories');
                Route::delete('{category}', [ProductCategoryController::class, 'destroy'])
                    ->middleware('permission:manage_categories');
            });

            Route::get('batches/expiring-soon', [BatchController::class, 'expiringSoon']);
            Route::prefix('products')->group(function () {
                Route::get('/', [ProductController::class, 'index'])
                    ->middleware('permission:manage_products');
                Route::post('/', [ProductController::class, 'store'])
                    ->middleware('permission:manage_products');
                Route::get('{product}', [ProductController::class, 'show'])
                    ->middleware('permission:manage_products');
                Route::patch('{product}', [ProductController::class, 'update'])
                    ->middleware('permission:manage_products');
                Route::patch('{product}/availability', [ProductController::class, 'updateAvailability'])
                    ->middleware('permission:manage_products');
                Route::delete('{product}', [ProductController::class, 'destroy'])
                    ->middleware('permission:manage_products');

                Route::get('{product}/batches', [BatchController::class, 'index'])
                    ->middleware('permission:manage_products');
                Route::post('{product}/batches', [BatchController::class, 'store'])
                    ->middleware('permission:manage_products');
                Route::patch('{product}/batches/{batch}', [BatchController::class, 'update'])
                    ->middleware('permission:manage_products');

                Route::get('{product}/stock-movements', [StockMovementController::class, 'index'])
                    ->middleware('permission:manage_products');
            });

            Route::prefix('suppliers')->group(function () {
                Route::get('/', [SupplierController::class, 'index'])
                    ->middleware('permission:manage_suppliers');
                Route::post('/', [SupplierController::class, 'store'])
                    ->middleware('permission:manage_suppliers');
                Route::patch('{supplier}', [SupplierController::class, 'update'])
                    ->middleware('permission:manage_suppliers');
                Route::delete('{supplier}', [SupplierController::class, 'destroy'])
                    ->middleware('permission:manage_suppliers');
            });

            Route::prefix('purchase-orders')->group(function () {
                Route::get('/', [PurchaseOrderController::class, 'index'])
                    ->middleware('permission:manage_purchase_orders');
                Route::post('/', [PurchaseOrderController::class, 'store'])
                    ->middleware('permission:manage_purchase_orders');
                Route::get('{purchaseOrder}', [PurchaseOrderController::class, 'show'])
                    ->middleware('permission:manage_purchase_orders');
                Route::post('{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])
                    ->middleware('permission:manage_purchase_orders');
                Route::post('{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])
                    ->middleware('permission:manage_purchase_orders');
            });

            Route::prefix('sales')->group(function () {
                Route::get('/', [SaleController::class, 'index'])
                    ->middleware('permission:view_sales');
                Route::post('/', [SaleController::class, 'store'])
                    ->middleware('permission:process_sales');
                Route::get('{sale}', [SaleController::class, 'show'])
                    ->middleware('permission:view_sales');
            });

            Route::prefix('pharmacy-codes')->group(function () {
                Route::get('/', [PharmacyCodeController::class, 'index'])
                    ->middleware('permission:generate_pharmacy_codes');
                Route::post('/', [PharmacyCodeController::class, 'store'])
                    ->middleware('permission:generate_pharmacy_codes');
            });

            Route::prefix('orders')->group(function () {
                Route::get('/', [OrderController::class, 'index'])
                    ->middleware('permission:manage_orders');
                Route::get('{order}', [OrderController::class, 'show'])
                    ->middleware('permission:manage_orders');
                Route::patch('{order}/status', [OrderController::class, 'updateStatus'])
                    ->middleware('permission:manage_orders');
                Route::patch('{order}/delivery-status', [OrderController::class, 'updateDeliveryStatus'])
                    ->middleware('permission:manage_orders');
            });

            Route::prefix('prescriptions')->group(function () {
                Route::get('/', [PrescriptionController::class, 'index'])
                    ->middleware('permission:manage_prescriptions');
                Route::get('{prescription}', [PrescriptionController::class, 'show'])
                    ->middleware('permission:manage_prescriptions');
                Route::get('{prescription}/file', [PrescriptionController::class, 'downloadFile'])
                    ->middleware('permission:manage_prescriptions');
                Route::patch('{prescription}/review', [PrescriptionController::class, 'review'])
                    ->middleware('permission:manage_prescriptions');
            });

            Route::prefix('customers')->group(function () {
                Route::get('/', [CustomerController::class, 'index'])
                    ->middleware('permission:manage_customers');
                Route::get('{customerPharmacyLink}', [CustomerController::class, 'show'])
                    ->middleware('permission:manage_customers');
                Route::get('{customerPharmacyLink}/orders', [CustomerController::class, 'orders'])
                    ->middleware('permission:manage_customers');
                Route::patch('{customerPharmacyLink}/suspend', [CustomerController::class, 'suspend'])
                    ->middleware('permission:manage_customers');
                Route::patch('{customerPharmacyLink}/unsuspend', [CustomerController::class, 'unsuspend'])
                    ->middleware('permission:manage_customers');
            });
        });
    });
});
