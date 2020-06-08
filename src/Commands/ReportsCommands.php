<?php

namespace Larapress\Reports\Commands;

use Larapress\Core\Commands\ActionCommandBase;
use Larapress\Reports\Services\IReportsService;

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
        ]);
    }

    public function syncInfluxDB() {
        return function () {
            /** @var IReportsService */
            $scheduler = app()->make(IReportsService::class);
            $scheduler->batchReportMeasurements(1000);
        };
    }
}
