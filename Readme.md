# ebolution/bigcommerce-app-adapter

Base functionality to connect an App with BigCommerce and to provide a way to make authorized calls to BigCommerce API.

## Pre-requisites

Define and configure your new App on https://devtools.bigcommerce.com/my/apps and note down your App's client ID and secret (View Client ID). 

## Installation 

1. Run command: `composer require ebolution/bigcommerce-app-adapter`
1. Run command: `php artisan migrate`
1. Run command: `php artisan vendor:publish --tag=bigcommerce-app-adapter --ansi`

## Configuration 

Required configuration on `config/session.php`:

```
'secure' => env('SESSION_SECURE_COOKIE', true),
'same_site' => env('SESSION_SAME_SITE', 'none'),
```

Values to add to your `.env` file:

```
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none

BC_APP_CLIENT_ID=<your-app-client-id>
BC_APP_SECRET=<your-app-client-secret>
```

Get `<your-app-client-id>` and `<your-app-client-secret>` from your App configuration at https://devtools.bigcommerce.com/my/apps

### Configuration for local development

To develop and test your application locally (rather than launched by a BigCommerce store), create an API account within your BigCommerce store (Settings > API Accounts) and set the following on your `.env` file:

```
APP_ENV=local

BC_LOCAL_CLIENT_ID=<api-account-client-id>
BC_LOCAL_SECRET=<api-account-client-secret>
BC_LOCAL_ACCESS_TOKEN=<api-account-access-token>
BC_LOCAL_STORE_HASH=<api-account-store-hash>
```
> The store hash is a part of the API PATH (https://api.bigcommerce.com/stores/<store-hash>/v3/)

> If `APP_ENV` is not set to `local`, then the BigCommerce API proxy uses values stored on the session 
to authorize API calls. For this to work the application should be launched by the BigCommerce store
via either the `auth` or `load` routes.

## Routes

### Routes to be configured on the App

Auth Callback UR: `https://<domain>/bc-app/auth`

Load Callback URL: `https://<domain>/bc-app/load`

Uninstall Callback URL: `https://<domain>/bc-app/uninstall`

Remove User Callback URL: `https://<domain>/bc-app/remove-user`

### Route to make authorized calls to BigCommerce

`https://<domain>/bc-app/bc-api/<big-commerce-api-endpoint>`

Where `<big-commerce-api-endpoint>` is something like `v2/store` or `v3/settings/store/locale`, following BigCommerce's API documentation (https://developer.bigcommerce.com/docs/rest)

# Security

This module does not enforce any particular security configuration. BigCommerce API calls are routed through
the BigCommerce API Proxy which runs under the `web` middleware. 

So if you need to add a security layer to your BigCommerce API calls, add required middlewares to `$middlewareGroups['web']` on `app\Http\Kernel.php`

In the case that your application requirements do not allow to modify the `web` middleware group:

1. Create a custom middleware with your security requirements. At the minimum, this middleware will include:
   - \App\Http\Middleware\EncryptCookies::class
   - \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class
   - \Illuminate\Session\Middleware\StartSession::class
   - \Illuminate\Routing\Middleware\SubstituteBindings::class
1. Set that middleware on the `middleware` setting inside `config/bigcommerce-app-adapter.php`
