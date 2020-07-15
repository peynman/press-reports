<?php

namespace Larapress\Reports\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Larapress\CRUD\Commands\ActionCommandBase;
use Larapress\CRUD\Events\CRUDVerbEvent;
use Larapress\Reports\CRUD\TaskReportsCRUDProvider;
use Larapress\Reports\Models\TaskReport;
use Larapress\Reports\Services\IReportsService;
use Larapress\Reports\Services\ITaskReportService;

class ReportsCommands extends ActionCommandBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larapress:reports {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'report events to influx db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct([
            'sync:influxdb' => $this->syncInfluxDB(),
            'tasks:queue' => $this->queueScheduledTasks(),
            'test' => $this->test(),
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function syncInfluxDB() {
        return function () {
            /** @var IReportsService */
            $scheduler = app()->make(IReportsService::class);
            $scheduler->batchReportMeasurements(1000);
        };
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function queueScheduledTasks() {
        return function () {
            /** @var ITaskReportService */
            $service = app(ITaskReportService::class);
            $service->queueScheduledTasks();
        };
    }

    public function test() {
        return function () {
            $task = TaskReport::find(15);
            CRUDVerbEvent::dispatch($task, TaskReportsCRUDProvider::class, Carbon::now(), 'queue');
        };
    }
}
