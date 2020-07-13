<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\CRUDControllers\BaseCRUDController;
use Larapress\Reports\CRUD\TaskReportsCRUDProvider;

class TaskReportController extends BaseCRUDController
{
    public static function registerRoutes()
    {
        parent::registerCrudRoutes(
            config('larapress.reports.routes.task_reports.name'),
            self::class,
            TaskReportsCRUDProvider::class
        );
    }
}
