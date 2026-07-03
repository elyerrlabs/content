<?php

/**
 * This file is part of the Content package.
 *
 * Shared menu definitions used across the Elymod ecosystem.
 *
 * Menus declared here are automatically merged and can be consumed by
 * core, system and third-party modules.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Shared Menu Definitions
    |--------------------------------------------------------------------------
    |
    | Menus declared under the "merge" key are global menu definitions.
    |
    | During boot, Elymod automatically merges these menus from every enabled
    | core, system and third-party module into a single shared configuration.
    |
    | This allows every module to contribute entries to common application
    | areas without requiring manual registration.
    |
    | Dashboard Menus
    | ---------------
    |
    | The following collections are automatically aggregated across all modules
    | and rendered in the corresponding dashboard section.
    |
    | For example:
    |
    | - admin_dashboard
    | - admin_routes
    | - user_routes
    | - admin_settings
    | - user_settings
    |
    | If five different modules define "admin_settings", the final menu will
    | contain the entries contributed by all five modules.
    |
    | Every menu entry is automatically filtered according to the configured
    | service scope, therefore a user only sees items for which permission
    | has been granted.
    |
    | In other words:
    |
    |     All modules contribute.
    |     All menus are merged.
    |     Users only see authorized entries.
    |
    | Required fields
    | ---------------
    |
    | id
    |     Unique menu identifier.
    |
    | name
    |     Display name.
    |
    | route
    |     Laravel named route.
    |
    | icon
    |     Material Design Icons class.
    |
    | service
    |     Optional permission required to display the menu.
    |
    |     Partial scope
    |
    |         settings:content
    |
    |     Matches any action belonging to that service.
    |
    |     Examples:
    |
    |         settings:content:view
    |         settings:content:edit
    |         settings:content:update
    |
    |     Strict scope
    |
    |         settings:content:view
    |
    |     Requires the exact permission.
    |
    */

    'merge' => [

        /*
        |--------------------------------------------------------------------------
        | Administrative Dashboard
        |--------------------------------------------------------------------------
        |
        | Entries displayed on the main administrator dashboard.
        |
        */
        'admin_dashboard' => [

            'content_admin' => [
                'id' => 'content-admin',
                'name' => 'Content Admin',
                'route' => 'module.content.admin.admin.index',
                'icon' => 'mdi mdi-store-cog',

                // Strict permission.
                'service' => 'administrator:content:view',
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | User Navigation
        |--------------------------------------------------------------------------
        |
        | Main application navigation for authenticated users.
        |
        */
        'user_routes' => [

            'content_users' => [
                'id' => 'content-users',
                'name' => 'Content Users',
                'route' => 'module.content.web.users.index',
                'icon' => 'mdi mdi-store-cog',

                // No permission required.
                // 'service' => null,
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Administrative Applications
        |--------------------------------------------------------------------------
        |
        | Applications available from the administration area.
        |
        */
        'admin_routes' => [

            'content-admin-app' => [
                'id' => 'content-app',
                'name' => 'Content',
                'route' => 'module.content.admin.admin.index',
                'icon' => 'mdi mdi-store-cog',

                // Strict permission.
                'service' => 'administrator:content:view',
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | User Settings
        |--------------------------------------------------------------------------
        |
        | Menu entries displayed under the user settings area.
        |
        */
        'user_settings' => [

            /*
            'content-settings' => [
                'id'      => 'content-settings',
                'name'    => 'Content Settings',
                'route'   => 'module.content.web.settings',
                'icon'    => 'mdi mdi-cog',

                // Any Content settings permission.
                'service' => 'settings:content',
            ],
            */

        ],

        /*
        |--------------------------------------------------------------------------
        | Administrator Settings
        |--------------------------------------------------------------------------
        |
        | Settings pages available from the administration panel.
        |
        */
        'admin_settings' => [

            'content-settings' => [
                'id' => 'content-settings',
                'name' => 'Content Settings',
                'route' => 'module.content.admin.settings.index',
                'icon' => 'mdi mdi-cog',

                // Strict permission (view only).
                'service' => 'settings:content:view',
            ],

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Module Navigation Collections
    |--------------------------------------------------------------------------
    |
    | Unlike the menus declared inside "merge", the collections below are NOT
    | automatically rendered in the application dashboard.
    |
    | They are intended to expose reusable navigation structures that can be
    | consumed directly inside a module interface.
    |
    | A common usage is with the Inertia helper:
    |
    |     resolveInertiaRoutes(config('menus.admin_routes'));
    |
    | or
    |
    |     resolveInertiaRoutes(config('menus.multimedia_routes'));
    |
    | Since these collections are also merged automatically across every
    | enabled module, using the same collection name allows multiple modules
    | to contribute entries to a shared navigation.
    |
    | Example
    | -------
    |
    | Module A
    |
    |     multimedia_routes
    |         - Images
    |         - Videos
    |         - Albums
    |
    | Module B
    |
    |     multimedia_routes
    |         - Audio
    |         - Playlists
    |         - Podcasts
    |
    | Final merged collection
    |
    |     multimedia_routes
    |         - Images
    |         - Videos
    |         - Albums
    |         - Audio
    |         - Playlists
    |         - Podcasts
    |
    | This makes it easy to build a unified navigation experience between
    | related modules while keeping each module completely independent.
    |
    | Recommendation
    | --------------
    |
    | If a navigation collection is intended to be private to a single module,
    | choose a unique collection name to avoid accidental merging with other
    | modules.
    |
    | Conversely, if several related modules should share the same navigation,
    | intentionally use the same collection key so their menus are combined
    | automatically.
    |
    */

    'admin_routes' => [

        /*
        [
            'id'       => 'content',
            'name'     => 'Content',
            'route'    => 'module.content.web.welcome',
            'icon'     => 'mdi-store-cog',
            // Partial match.
            'service'  => 'settings:content',
            'position' => 8,
        ],
        */

    ],

];