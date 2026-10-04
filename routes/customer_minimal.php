<?php

use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::view('login', 'elegant.customer.login');
     Route::view('privacy-policy','elegant.legalPages.Customer-facing.privacyPolicy')->name('privacyPolicy');
     Route::view('Terms-and-condition','elegant.legalPages.Customer-facing.termsAndCondition')->name('termsAndCondition');;
     Route::view('refund-and-cancellation-policy','elegant.legalPages.Customer-facing.refundAndCancellationPolicy')->name('refundAndCancellationPolicy');;
     Route::view('cookie-policy','elegant.legalPages.Customer-facing.cookiePolicy')->name('cookiePolicy');;
     Route::view('disclaimer','elegant.legalPages.Customer-facing.disclaimer')->name('disclaimer');;
    Route::view('register', 'elegant.customer.register');
    Route::view('forgot-password', 'elegant.customer.forgot-password');
    Route::view('reset-password', 'minimal.customer.reset-password');
    Route::view('verify-email', 'minimal.customer.verify-email');
    Route::view('pharmacies', 'minimal.customer.pharmacies.index');
    Route::view('pharmacies/join', 'elegant.customer.pharmacies.join');
    Route::view('products', 'elegant.customer.products.index');
    Route::view('products/{id}', 'elegant.customer.products.show');
    Route::view('cart', 'elegant.customer.cart.index');
    Route::view('checkout', 'elegant.customer.checkout.index');
    Route::view('orders', 'elegant.customer.orders.index');
    Route::view('extra', 'elegant.customer.extra')->name('extra');
    Route::view('support', 'elegant.customer.support')->name('support');
      Route::view('orders/{id}', 'elegant.customer.orders.show');
    // Route::view('prescriptions/upload', 'minimal.customer.prescriptions.upload');
    Route::view('notifications', 'elegant.customer.notifications.index');
    Route::view('profile', 'elegant.customer.profile')->name('profile');
    Route::get('payment-callback', function () {
        return view('elegant.customer.payment-callback');
    });
});