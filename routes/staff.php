<?php

use Illuminate\Support\Facades\Route;


Route::prefix('staff')->group(function () {

    Route::view('login', 'elegant.staff.login')->name('login');
    Route::view('forgot-password', 'elegant.staff.forgot-password');
    // Route::view('reset-password', 'minimal.staff.reset-password');
    Route::view('dashboard', 'elegant.staff.dashboard');
    // Route::view('onboarding', 'minimal.pharmacy.onboarding');
    Route::view('products', 'elegant.staff.products');
    Route::view('product-details', 'elegant.staff.product-details');
    Route::view('product-categories', 'elegant.staff.product-categories');
    Route::view('suppliers', 'elegant.staff.suppliers');
    Route::view('purchase-orders', 'elegant.staff.purchase-orders');
    Route::view('purchase-order-create', 'elegant.staff.purchase-order-create');
    Route::view('purchase-order-details', 'elegant.staff.purchase-order-details');
    Route::view('sales', 'elegant.staff.sales');
    Route::view('pos', 'elegant.staff.pos');
    Route::view('pharmacy-codes', 'elegant.staff.pharmacy-codes');
    Route::view('orders', 'elegant.staff.orders');
    Route::view('order-details', 'elegant.staff.order-details');
    // Route::view('prescriptions', 'minimal.staff.prescriptions');
    // Route::view('prescription-details', 'minimal.staff.prescription-details');
    Route::view('customers', 'elegant.staff.customers');
    // Route::view('customer-details', 'minimal.staff.customer-details');
    // Route::view('subscription', 'minimal.staff.subscription');
    Route::view('staff-management', 'elegant.staff.staff-management');
    // Route::view('settlement', 'minimal.staff.settlement');
    // Route::view('customer-create', 'minimal.staff.customer-create');
    // Route::view('profile', 'minimal.staff.profile');
    // Route::view('pharmacy-settings', 'minimal.staff.pharmacy-settings');
    Route::view('expiring-batches', 'elegant.staff.expiring-batches');
});

Route::view('register', 'elegant.pharmacy.register')->name('register');
Route::get('/payment-callback', function () {
    return view('payment.callback');
});
