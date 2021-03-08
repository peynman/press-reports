# W.I.P.

# Larapress Reports
A package to provide automatic and customized metrics and reporting for Larapress CRUD and other packages based on it.

## Dependencies
* [Larapress CRUD](../../../larapress-crud)

## Install
1. ```composer require peynman/larapress-reports```

## Config
1. Run ```php artisan vendor:publish --tag=larapress-reports```
1. Update ``broadcasting.connections.pusher`` to
    ````php
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => null,
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => null,
        'encrypted' => true,
        'host' => env('ECHO_HOST', 'laravel-echo'),
        'port' => env('ECHO_PORT', 8443),
        'scheme' => env('ECHO_PROTOCOL', 'http')
    ],
    ````

## Usage
