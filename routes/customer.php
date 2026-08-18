<?php

use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::view('login', 'minimal.customer.login');
    Route::view('register', 'minimal.customer.register');
    Route::view('forgot-password', 'minimal.customer.forgot-password');
    Route::view('reset-password', 'minimal.customer.reset-password');
    Route::view('verify-email', 'minimal.customer.verify-email');
    Route::view('pharmacies', 'minimal.customer.pharmacies.index');
    Route::view('pharmacies/join', 'minimal.customer.pharmacies.join');
    Route::view('products', 'minimal.customer.products.index');
    Route::view('products/{id}', 'minimal.customer.products.show');
    Route::view('cart', 'minimal.customer.cart.index');
    Route::view('checkout', 'minimal.customer.checkout.index');
    Route::view('orders', 'minimal.customer.orders.index');
    Route::view('orders/{id}', 'minimal.customer.orders.show');
    Route::view('prescriptions', 'minimal.customer.prescriptions.index');
    Route::view('prescriptions/upload', 'minimal.customer.prescriptions.upload');
    Route::view('notifications', 'minimal.customer.notifications.index');
    Route::view('profile', 'minimal.customer.profile');
    Route::get('payment-callback', function () {
        return view('minimal.customer.payment-callback');
    });
});