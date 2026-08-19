<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingPage.home');
});

//mockup pages 
require __DIR__ . '/customer_minimal.php';
require __DIR__ . '/staff_minimal.php';

//stable ui
require __DIR__ . '/staff.php';

