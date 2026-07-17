<?php
use Content\App\Http\Controllers\web\PoliciesController;
use Illuminate\Support\Facades\Route;
use Content\App\Http\Controllers\web\PageController;
/**
 * The public.php routes file is intended for defining routes 
 * that need to exist **outside of the module's default URI prefix** 
 * or require a **custom URI prefix** different from the module name.
 * 
 * These routes can be **public** or **protected (authenticated)**, depending on your needs.
 * Use this file when you want more flexibility in route structure without being tied to the module path.
 * 
 * ⚠️ Caution: Overusing public.php or placing unrelated routes here 
 * can lead to route clutter, weak modular boundaries, and maintenance issues.
 * Always evaluate whether a route truly requires a custom/global path.
 */


Route::middleware(['throttle:system:general:public'])->group(function () {

    Route::group([
        'prefix' => 'legal',
        'as' => 'legal.',
    ], function () {
        Route::get('terms-and-conditions', [PoliciesController::class, 'termsAndCondition'])->name('terms-and-conditions');
        Route::get('policies-of-privacy', [PoliciesController::class, 'policiesOfPrivacy'])->name('policies-of-privacy');
        Route::get('policies-of-cookies', [PoliciesController::class, 'policiesOfCookies'])->name('policies-of-cookies');
    });

    // Load dinamic pages
    Route::get('/{locale?}/{slug?}', [PageController::class, 'page'])->name('pages');
});