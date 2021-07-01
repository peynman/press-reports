<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\Services\CRUD\CRUDController;
use Larapress\Reports\CRUD\LaravelEchoCRUDProvider;


/**
 * Standard CRUD Controller for Laravel Echo resource.
 *
 * @group Laravel Echo Management
 */
class LaravelEchoController extends CRUDController
{
    public static function registerRoutes()
    {
        parent::registerCrudRoutes(
            config('larapress.reports.routes.laravel_echo.name'),
            self::class,
            config('larapress.reports.routes.laravel_echo.provider'),
        );
    }
}
