<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <h4>{{ __('bigcommerce-app-adapter::messages.heading') }}:</h4>
            <p>{{ $error_message }}</p>
            <a href="{{ $base_url }}">{{ __('bigcommerce-app-adapter::messages.go_back') }}</a>
        </div>
    </body>
</html>
