# ebolution/bigcommerce-app-adapter

Base functionality to connect an App with BigCommerce and to provide a way to make authorized calls to BigCommerce API.

## Pre-requisites

Define and configure your new App on https://devtools.bigcommerce.com/my/apps and note down your App's client ID and secret (View Client ID). 

## Installation 

1. Run command: `composer require ebolution/bigcommerce-app-adapter`
1. Run command: `php artisan migrate`
1. Run command: `php artisan vendor:publish --tag=bigcommerce-app-adapter --ansi`

## Configuration 

Values to add to your `.env` file:

```
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

> If `APP_ENV` is not set to `local`, then the BigCommerce API proxy uses values stored on the database 
to authorize API calls. For this to work the application should be launched by the BigCommerce store
via either the `auth` or `load` routes (see **Security** section).

## Routes

### Routes to be configured on the App

Auth Callback UR: `https://<domain>/api/auth`

Load Callback URL: `https://<domain>/api/load`

Uninstall Callback URL: `https://<domain>/api/uninstall`

Remove User Callback URL: `https://<domain>/api/remove-user`

### Route to make authorized calls to BigCommerce (Proxy)

`https://<domain>/api/bc-api/<big-commerce-api-endpoint>`

Where `<big-commerce-api-endpoint>` is something like `v2/store` or `v3/settings/store/locale`, following BigCommerce's API documentation (https://developer.bigcommerce.com/docs/rest)

# Security

This module uses JWT Tokens to validate request made from the front-end application to BigCommerce API.

Summary of the flow:
1. BigCommerce loads the application on behalf of a user.
1. The module generates a JWT token that contains the ID of the user.
1. The module brings control to the front-end and sends the JWT token as the 'token' parameter (on the query string).
1. The front-end application persists the token for later use.
1. When the front-end application needs to make an authorized call to BigCommerce API, it passes the token on the `X-Auth-Token` request header to the proxy end-point.
1. The proxy validates the token and retrieves other information required to pass the call to BigCommerce API.
1. Request is sent to BigCommerce and response is passed back to the front-end application.

The token is valid for a period of `token_duration_hours` hours (see `config/bigcommerce-app-adapter.php`).

> The level of access to BigCommerce API is restricted by the scopes assigned to the App when it was configured.
