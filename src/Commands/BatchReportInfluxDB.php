<?php

namespace Larapress\Reports\Commands;

use Illuminate\Console\Command;
use Larapress\Reports\Services\IReportsService;

class BatchReportInfluxDB extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:reports:report-influxdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report InfluxDB measurements batched in redis database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        /** @var IReportsService */
        $scheduler = app()->make(IReportsService::class);
        $scheduler->batchReportMeasurements(256);

        return 0;
    }
}
