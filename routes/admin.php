<?php

use Content\App\Http\Controllers\Admin\FileController;
use Illuminate\Support\Facades\Route;
use Content\App\Http\Controllers\Admin\SitemapController;
use Content\App\Http\Controllers\Admin\SeoController;
use Content\App\Http\Controllers\Admin\PoliciesController;
use Content\App\Http\Controllers\Admin\LayoutController;
use Content\App\Http\Controllers\Admin\PageController;
use Content\App\Http\Controllers\Admin\SettingsController;

/**
 * Register admin routes
 */

Route::middleware("throttle:third-party:content:admin")->group(function () {

    Route::post('/pages/generate-sitemap', [PageController::class, 'generateSitemapFile'])->name('pages.generate-sitemap');
    Route::post('/pages/{page}/reset', [PageController::class, 'reset'])->name('pages.reset');
    Route::resource('pages', PageController::class);


    Route::get('layouts', [LayoutController::class, 'form'])->name('layouts.schema');
    Route::put('layouts', [LayoutController::class, 'update'])->name('layouts.update');
    Route::get('policies', [PoliciesController::class, 'form'])->name('policies.schema');
    Route::get('seo', [SeoController::class, 'form'])->name('seo.schema');

    Route::resource('files', FileController::class)->only('index', 'store', 'destroy', 'update');

    Route::group([
        'prefix' => 'sitemaps',
        'as' => 'sitemaps.',
        'middleware' => ['throttle:system:general:settings', 'password.confirm']
    ], function () {

        Route::get('/routes', [SitemapController::class, 'index'])->name('index');
        Route::post('/routes', [SitemapController::class, 'updateMeta'])->name('store');
        Route::delete('/routes/reset', [SitemapController::class, 'reset'])->name('reset');

        Route::get('/robot', [SitemapController::class, 'robotForm'])->name('robot.form');
        Route::post('/robot', [SitemapController::class, 'updateRobot'])->name('robot.update');

    });


    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/update', [SettingsController::class, 'update'])->name('update');
    });
});
