<?php
/**
 * This file is part of the Elyerr Core Identity package.
 *
 * It defines middleware aliases that can be used across
 * core, system, and third-party modules.
 *
 * All declared middlewares must be registered here in order
 * to be resolved by the Laravel router using their alias.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Register custom middlewares using an alias.
    | Once registered, they can be used like any native
    | Laravel middleware within module routes.
    |
    | Example:
    |   Route::middleware(['elymod'])->group(...)
    |
    */

    // 'elymod' => \Elymod\App\Http\Middleware\Elymod::class,

];
