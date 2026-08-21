<?php

use Illuminate\Support\Facades\Route;


Route::prefix('staff')->group(function () {
    // Route::view('login', 'minimal.staff.login');
    Route::view('forgot-password', 'minimal.staff.forgot-password');
    Route::view('reset-password', 'minimal.staff.reset-password');
    Route::view('dashboard', 'minimal.staff.dashboard');
    Route::view('onboarding', 'minimal.pharmacy.onboarding');
    // Route::view('products', 'minimal.staff.products');
    // Route::view('product-details', 'minimal.staff.product-details');
    // Route::view('product-categories', 'minimal.staff.product-categories');
    // Route::view('suppliers', 'minimal.staff.suppliers');
    // Route::view('purchase-orders', 'minimal.staff.purchase-orders');
    // Route::view('purchase-order-create', 'minimal.staff.purchase-order-create');
    // Route::view('purchase-order-details', 'minimal.staff.purchase-order-details');
    // Route::view('sales', 'minimal.staff.sales');
    // Route::view('pos', 'minimal.staff.pos');
    // Route::view('pharmacy-codes', 'minimal.staff.pharmacy-codes');
    // Route::view('orders', 'minimal.staff.orders');
    // Route::view('order-details', 'minimal.staff.order-details');
    Route::view('prescriptions', 'minimal.staff.prescriptions');
    Route::view('prescription-details', 'minimal.staff.prescription-details');
    // Route::view('customers', 'minimal.staff.customers');
    Route::view('customer-details', 'minimal.staff.customer-details');
    Route::view('subscription', 'minimal.staff.subscription');
    // Route::view('staff-management', 'minimal.staff.staff-management');
    Route::view('settlement', 'minimal.staff.settlement');
    Route::view('customer-create', 'minimal.staff.customer-create');
    Route::view('profile', 'minimal.staff.profile');
    Route::view('pharmacy-settings', 'minimal.staff.pharmacy-settings');
    // Route::view('expiring-batches', 'minimal.staff.expiring-batches');
});

// Route::view('register', 'minimal.pharmacy.register');
Route::get('/payment-callback', function () {
    return view('payment.callback');
});
