<?php

use Illuminate\Support\Facades\Route;

/**
 * Register routes API
 */

Route::middleware(["throttle:third-party:content:api"])->group(function () {

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {
        /**
         * Admin routes
         */

    });


    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        /**
         * List users routes
         */

    });
});
