<?php

return [
    'oauth' => [
        'client_id'     => env('MYOB_CLIENT_ID', ''),
        'client_secret' => env('MYOB_CLIENT_SECRET', ''),
        'domainPrefix' => env('MYOB_DOMAIN_PREFIX', ''),
        'scopes'        => env('MYOB_SCOPE', 'CompanyFile'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel MYOB Oauth Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Laravel Xero oAuth will be accessible from. Feel free
    | to change this path to anything you like.
    |
    */

    'path' => env('LARAVEL_MYOB_PATH', 'myob'),

    /*
    |--------------------------------------------------------------------------
    | Laravel MYOB oAuth Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Laravel Xero oAuth route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    | ** EXCEPTION **
    | The callback route used by MYOB will be excluded from this middleware
    |
    */

    'middleware' => ['web', 'auth'],
    'exclude_middleware_for_callback' => ['auth'],

];
