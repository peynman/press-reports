<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\Services\CRUD\BaseCRUDController;
use Larapress\Reports\CRUD\MetricsCRUDProvider;

/**
 * Standard CRUD Controller for Metrics resource.
 *
 * @group Metrics Management
 */
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
