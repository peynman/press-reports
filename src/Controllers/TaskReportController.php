<?php

namespace Larapress\Reports\Controllers;

use Illuminate\Routing\Controller;
use Larapress\Reports\Services\TaskScheduler\ITaskSchedulerService;
use Larapress\Reports\Models\TaskReport;

/**
 * @group Task Reports
 */
class TaskReportController extends Controller
{
    /**
     * Queue Task
     *
     * @param ITaskSchedulerService $service
     * @param int $id
     *
     * @return TaskReport
     */
    public function queueTask(ITaskSchedulerService $service, $id)
    {
        return $service->queueScheduledTask($id);
    }
}
