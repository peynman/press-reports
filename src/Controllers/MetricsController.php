<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\CRUDControllers\BaseCRUDController;
use Larapress\Reports\CRUD\MetricsCRUDProvider;

class MetricsController extends BaseCRUDController
{
    public static function registerRoutes()
    {
        parent::registerCrudRoutes(
            config('larapress.reports.routes.metrics.name'),
            self::class,
            MetricsCRUDProvider::class,
            []
        );
    }
}
