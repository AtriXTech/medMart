<?php

use Illuminate\Support\Facades\Route;


Route::prefix('superAdmin')->group(function(){

Route::view('dashboard', 'elegant.superAdmin.dashboard')->name('dashboard');
    Route::view('pharmacies', 'elegant.superAdmin.pharmacies')->name('pharmacies');
    Route::view('pharmacyDetails', 'elegant.superAdmin.pharmacy-details')->name('pharmacyDetails');
    Route::view('customers', 'elegant.superAdmin.customers')->name('customs');
    Route::view('customerDetails', 'elegant.superAdmin.customer-details')->name('customerDetails');
    Route::view('orders', 'elegant.superAdmin.orders')->name('orders');
    Route::view('ordersDetails', 'elegant.superAdmin.order-details')->name('orderDetails');
    Route::view('transactions', 'elegant.superAdmin.transactions')->name('transactions');
    Route::view('transactionDetails', 'elegant.superAdmin.transaction-details')->name('transactionDetails');
    Route::view('settlements', 'elegant.superAdmin.settlements')->name('settlements');
    Route::view('settlementDetails', 'elegant.superAdmin.settlement-details')->name('settlementDetails');
    Route::view('subscriptionPlans', 'elegant.superAdmin.subscriptionPlans.index')->name('subscriptionPlans');
    Route::view('subscriptionPlanShow', 'elegant.superAdmin.subscriptionPlans.show')->name('subscriptionPlanShow');
    Route::view('subscriptions', 'elegant.superAdmin.subscriptions.index')->name('subscriptions');
    Route::view('subscriptionShow', 'elegant.superAdmin.subscriptions.show')->name('subscriptionShow');
    Route::view('adminUsers', 'elegant.superAdmin.Administration.adminUsers')->name('adminUsers');
    Route::view('adminUserDetails', 'elegant.superAdmin.Administration.adminUser-details')->name('adminUserDetails');
    Route::view('settings', 'elegant.superAdmin.Administration.settings')->name('settings');
    Route::view('login', 'elegant.superAdmin.Administration.login')->name('login');

});