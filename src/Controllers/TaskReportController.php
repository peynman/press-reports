<?php

namespace Larapress\Reports\Controllers;

use Illuminate\Http\Request;
use Larapress\CRUD\CRUDControllers\BaseCRUDController;
use Larapress\Reports\CRUD\TaskReportsCRUDProvider;
use Larapress\Reports\Services\ITaskReportService;
use Larapress\Reports\Models\TaskReport;

class TaskReportController extends BaseCRUDController
{
    public static function registerRoutes()
    {
        parent::registerCrudRoutes(
            config('larapress.reports.routes.task_reports.name'),
            self::class,
            TaskReportsCRUDProvider::class,
            [
                'queue' => [
                    'uses' => '\\'.self::class.'@queueTask',
                    'methods' => ['POST'],
                    'url' => config('larapress.reports.routes.task_reports.name').'/queue/{id}',
                ]
            ]
        );
    }


    /**
     * Undocumented function
     *
     * @param ITaskReportService $service
     * @param int $id
     * @return TaskReport
     */
    public function queueTask(ITaskReportService $service, $id) {
        return $service->queueScheduledTask($id);
    }
}