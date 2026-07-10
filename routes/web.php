<?php

use Illuminate\Support\Facades\Route;

Route::middleware(["throttle:third-party:content:web"])->group(function () {


});