<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

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
    | Token Duration
    |--------------------------------------------------------------------------
    |
    | JWT token issued by this module are valid for this number of hours
    |
    */

    'token_duration_hours' => 8,

];
