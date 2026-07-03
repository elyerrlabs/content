<?php

use Illuminate\Support\Facades\Route;
use Content\App\Http\Controllers\SettingsController;

/**
 * Register admin routes
 */

Route::middleware("throttle:third-party:content:admin")->group(function () {

    Route::get('/admin', [
        \Content\App\Http\Controllers\AdminController::class,
        'index'
    ])->name('admin.index');



    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/update', [SettingsController::class, 'update'])->name('update');
    });
});
