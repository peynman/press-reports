<?php

namespace Larapress\Reports\Controllers;

use Larapress\CRUD\Services\CRUD\CRUDController;
use Larapress\Reports\CRUD\TaskReportsCRUDProvider;
use Larapress\Reports\Services\TaskScheduler\ITaskSchedulerService;
use Larapress\Reports\Models\TaskReport;

/**
 * Standard CRUD Controller for TaskReports resource.
 *
 * @group Task Reports
 */
class TaskReportController extends CRUDController
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
     * Queue Task
     *
     * @param ITaskReportService $service
     * @param int $id
     *
     * @return TaskReport
     */
    public function queueTask(ITaskReportService $service, $id)
    {
        return $service->queueScheduledTask($id);
    }
}
