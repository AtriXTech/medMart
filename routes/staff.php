<?php

use Illuminate\Support\Facades\Route;


Route::prefix('staff')->group(function () {

    Route::view('login', 'elegant.staff.login')->name('login');
    Route::view('forgot-password', 'elegant.staff.forgot-password');
    Route::view('reset-password', 'elegant.staff.reset-password');
    Route::view('dashboard', 'elegant.staff.dashboard');
    Route::view('onboarding', 'elegant.pharmacy.onboarding');
    Route::view('products', 'elegant.staff.products');
    Route::view('product-details', 'elegant.staff.product-details');
    Route::view('out-of-stock','elegant.staff.out-of-stock');
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
    Route::view('customers', 'elegant.staff.customers')->name('customers');
    Route::view('customer-details', 'elegant.staff.customer-details');
    Route::view('subscription', 'elegant.staff.subscription');
    Route::view('staff-management', 'elegant.staff.staff-management');
    Route::view('settlement', 'elegant.staff.settlement');
    Route::view('customer-create', 'elegant.staff.customer-create')->name('createCustomer');
    Route::view('profile', 'elegant.staff.profile');
    Route::view('pharmacy-settings', 'elegant.staff.pharmacy-settings');
    Route::view('expiring-batches', 'elegant.staff.expiring-batches');
});

Route::view('register', 'elegant.pharmacy.register')->name('register');
Route::get('/payment-callback', function () {
    return view('elegant.payment.callback');
});
