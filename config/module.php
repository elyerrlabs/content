<?php
/**
 * This file is part of the Content}} package.
 *
 * It defines the base configuration for the identity module.
 * These settings control module metadata and activation state.
 * 
 * All module are loaded dynamically and can be adjusted
 * at any time without modifying route definitions.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Module Name
    |--------------------------------------------------------------------------
    |
    | Human-readable name used for display purposes.
    |
    */
    'name' => 'Content Module',

    /*
    |--------------------------------------------------------------------------
    | Module Enabled
    |--------------------------------------------------------------------------
    |
    | Determines whether the module is active.
    | Disabled modules will not register routes, menus, or services.
    |
    */
    'module_enabled' => true,

];
