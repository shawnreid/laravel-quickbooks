<?php

return [

    /**
     * Properties for the QuickBooks SDK DataService
     *
     * https://intuit.github.io/QuickBooks-V3-PHP-SDK/configuration.html
     */
    'connection' => [
        'base_url'      => env('QUICKBOOKS_API_URL', config('app.env') === 'production' ? 'Production' : 'Development'),
        'client_id'     => env('QUICKBOOKS_CLIENT_ID'),
        'client_secret' => env('QUICKBOOKS_CLIENT_SECRET'),
        'redirect_url'  => env('QUICKBOOKS_REDIRECT_URL'),
        'scope'         => 'com.intuit.quickbooks.accounting',
    ],

    /**
     * Configures logging to <storage_path>/logs
     * when QUICKBOOKS_DEBUG is true.
     */
    'logging' => [
        'enabled'  => env('QUICKBOOKS_DEBUG', config('app.debug')),
        'location' => storage_path('logs'),
    ],

    /**
     * Model which OAuth tokens can be attached to.
     *
     * Model will also need Shawnreid\LaravelQuickbooks\Quickbooks trait.
     */
    'relation' => [

        // Model quickbooks will attach to.
        'model' => App\Models\User::class,

        // Model key used in relation.
        'key'   => 'id',

        // Value displayed in package interface.
        'value' => 'email',
    ],

    /**
     * Properties to configure routes
     */
    'route' => [

        'middleware' => [
            // Added to the protected routes for the package
            'authenticated' => 'auth',

            // Added to all of the routes for the package
            'default'       => 'web',
        ],
    ],
];
