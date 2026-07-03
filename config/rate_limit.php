<?php
/**
 * This file is part of the Elymod package.
 *
 * It defines rate limit configurations used to protect routes
 * against abuse and brute-force attacks.
 *
 * Rate limits are applied using the `throttle` middleware with
 * the following naming convention:
 *
 *   third-party:{moduleName}:{rate_limit_name}
 *
 * Example:
 *   throttle:third-party:content:web
 *
 * Module developers can review existing modules to see
 * real-world usage examples.
 *
 * All rate limits are loaded dynamically and can be adjusted
 * at any time without modifying route definitions.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Routes Rate Limit
    |--------------------------------------------------------------------------
    |
    | Applied to administrative dashboards and protected areas.
    |
    */
    'admin' => [
        'limit' => 300,   // Maximum requests allowed
        'block_time' => 120,   // Block duration in minutes
        'name' => 'Rate limit for admin routes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Web Routes Rate Limit
    |--------------------------------------------------------------------------
    |
    | Applied to standard web routes.
    |
    */
    'web' => [
        'limit' => 300,
        'block_time' => 120,
        'name' => 'Rate limit for web routes',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Routes Rate Limit
    |--------------------------------------------------------------------------
    |
    | Applied to administrative or protected API endpoints.
    |
    */
    'api' => [
        'limit' => 300,
        'block_time' => 120,
        'name' => 'Rate limit for API routes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Rate Limits
    |--------------------------------------------------------------------------
    |
    | Module developers may define additional rate limits as needed.
    | These will be automatically registered and made available
    | through the throttle middleware.
    |
    */

    // 'custom_name' => [
    //     'limit'      => 100,
    //     'block_time' => 60,
    //     'name'       => 'Custom rate limit description',
    // ],

];
