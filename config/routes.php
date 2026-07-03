<?php
/**
 * This file is part of the Content package.
 *
 * It defines configuration flags used to enable or disable
 * specific routes or services at runtime.
 *
 * These flags are loaded dynamically and can be toggled
 * without modifying route definitions.
 *
 * Configuration access format:
 *
 *   config('module.third-party.{module}.{service}.status', true)
 *
 * By default, every declared service is enabled (`true`)
 * unless explicitly disabled.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Route / Service Feature Flags
    |--------------------------------------------------------------------------
    |
    | Declare feature flags using the following structure:
    |
    |  'service_name' => [
    |      'status' => true,
    |      'description' => 'Human-readable description'
    |  ]
    |
    | These flags can be used to conditionally register routes,
    | middleware, or entire service features.
    |
    */

    // Example configuration entries

    /*
    'subscriptions' => [
        'status' => true,
        'description' => 'Enable or disable subscription routes for users',
    ],

    'plans' => [
        'status' => true,
        'description' => 'Enable or disable plan-related routes',
    ],
    */

];
