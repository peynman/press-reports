<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\CRUDControllers\BaseCRUDController;
use Larapress\Reports\CRUD\LaravelEchoCRUDProvider;

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
