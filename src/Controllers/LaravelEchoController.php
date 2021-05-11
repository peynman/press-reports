<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\Services\CRUD\BaseCRUDController;
use Larapress\Reports\CRUD\LaravelEchoCRUDProvider;


/**
 * Standard CRUD Controller for Laravel Echo resource.
 *
 * @group Laravel Echo Management
 */
class LaravelEchoController extends BaseCRUDController
{
    public static function registerRoutes()
    {
        parent::registerCrudRoutes(
            config('larapress.reports.routes.laravel_echo.name'),
            self::class,
            LaravelEchoCRUDProvider::class,
            []
        );
    }
}
