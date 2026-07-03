<?php

use Illuminate\Support\Facades\Route;

Route::middleware(["throttle:third-party:content:web"])->group(function () {

    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {

        Route::get(
            '/',
            [\Content\App\Http\Controllers\UserController::class, 'index']
        )->name('index');
    });
});