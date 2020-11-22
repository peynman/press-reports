<?php

namespace Larapress\Reports\Commands;

use Larapress\CRUD\Commands\ActionCommandBase;
use Larapress\Reports\Services\LaravelEcho\ILaravelEchoMetrics;
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
            'sync:purge' => $this->syncInfluxDBPurge(),
            'tasks:queue' => $this->queueScheduledTasks(),
            'echo:grab' => $this->grabEchoStatistics(),
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function syncInfluxDB() {
        return function () {
            ini_set('memory_limit', '1024M');
            /** @var IReportsService */
            $scheduler = app()->make(IReportsService::class);
            $scheduler->batchReportMeasurements(256);
        };
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function syncInfluxDBPurge() {
        return function () {
            /** @var IReportsService */
            $scheduler = app()->make(IReportsService::class);
            $scheduler->barchReportPurge();
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

    public function grabEchoStatistics() {
        return function () {
            /** @var ILaravelEchoMetrics */
            $service = app(ILaravelEchoMetrics::class);
            $service->pushEchoMeasurements();
        };
    }
}
