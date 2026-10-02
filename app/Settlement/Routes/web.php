<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

if ((bool) config('settlement.ui.admin_console')) {
    Route::middleware('web')->group(function () {
        Route::view((string) config('settlement.ui.admin_console_path'), 'settlement::admin.console')
            ->name('settlement.admin.console');
    });
}
