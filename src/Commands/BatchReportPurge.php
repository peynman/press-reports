<?php

namespace Larapress\Reports\Commands;

use Illuminate\Console\Command;
use Larapress\Reports\Services\Reports\IReportsService;

class BatchReportPurge extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:reports:purge-influxdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge InfluxDB measurements batched in redis database.';

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
        /** @var IReportsService */
        $scheduler = app()->make(IReportsService::class);
        $scheduler->batchReportPurge();

        return 0;
    }
}
