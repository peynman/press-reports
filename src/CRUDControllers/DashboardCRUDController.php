<?php

namespace Larapress\Dashboard\CRUDControllers;

use Larapress\CRUD\CRUDControllers\BaseCRUDController;
use Larapress\Dashboard\CRUD\DashboardCRUDProvider;

class DashboardCRUDController extends BaseCRUDController
{
    public static function registerRoutes()
    {
        self::registerCrudRoutes(
            'dashboard',
            self::class,
            DashboardCRUDProvider::class
        );
    }
}