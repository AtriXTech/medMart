<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Pharmacy\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('pharmacy')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
});