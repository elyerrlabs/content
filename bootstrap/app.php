<?php

use Elyerr\LaravelRuntime\App\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
      //  Content\App\Providers\ElyscopeServiceProvider::class,
      //  Content\App\Providers\ModuleServiceProvider::class,
       // Content\App\Providers\RouteServiceProvider::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
