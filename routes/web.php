<?php

use Content\App\Http\Controllers\web\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware(["throttle:third-party:content:web"])->group(function () {

    Route::get('files/{id}', [FileController::class, 'renderFile'])->name('render.file');
});