# W.I.P.

# Larapress Reports
A package to provide automatic and customized metrics and reporting for Larapress CRUD and other packages based on it.

## Dependencies
* Larapress CRUD

## Install
1. ```composer require ```

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
1. Add broadcasting auth endpoint to ``api.php``
    ````php
    Route::middleware(['api', 'auth:api',])->post('/broadcast/auth', function (Request $request) {
        /** @var IBaseCRUDBroadcast */
        $service = app(IBaseCRUDBroadcast::class);
        return $service->authenticateRequest($request);
    });
    ````
1. Add CRUD authrization channels to ``channels.php``
    ````php
    // general crud permissions
    Broadcast::channel('crud.{name}.{verb}', function (ICRUDUser $user, $name, $verb) {
        /** @var IBaseCRUDBroadcast */
        $service = app(IBaseCRUDBroadcast::class);
        return $service->authorizeForCRUDChannel($user, $name, $verb);
    }, ['guards' => ['web', 'api']]);

    // general crud permissions or
    Broadcast::channel('crud.{name}.{verb}.${id}', function (ICRUDUser $user, $name, $verb, $uid) {
        /** @var IBaseCRUDBroadcast */
        $service = app(IBaseCRUDBroadcast::class);
        return $service->authorizeForCRUDSupportChannel($user, $name, $verb, $uid);
    }, ['guards' => ['web', 'api']]);

    ````

## Usage
* 
