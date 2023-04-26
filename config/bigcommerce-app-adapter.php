<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth Endpoint
    |--------------------------------------------------------------------------
    |
    | This is the endpoint where BigCommerce process OAuth authentication
    | requests.
    |
    */

    'oauth_endpoint' => 'https://login.bigcommerce.com/oauth2/token',

    /*
    |--------------------------------------------------------------------------
    | Login base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for BigCommere login portal.
    |
    */

    'login_base_url' => 'https://login.bigcommerce.com',

    /*
    |--------------------------------------------------------------------------
    | API base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for BigCommere API.
    |
    */

    'api_base_url' => 'https://api.bigcommerce.com',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware to use for routes handled by this module.
    |
    */

    'middleware' => 'web',
];
