<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage.home');
})->name('home');
Route::get('/pricing',function(){
return view('landingPage.pricing');
})->name('pricing');
Route::get('/contact',function(){
    return view('landingPage.contact');
})->name('contact');

//mockup pages 
require __DIR__ . '/customer_minimal.php';
require __DIR__ . '/staff_minimal.php';

//stable ui
require __DIR__ . '/staff.php';

