<?php

namespace Larapress\Reports\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Larapress\CRUD\Commands\ActionCommandBase;
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
            'test' => $this->test(),
        ]);
    }

    public function syncInfluxDB() {
        return function () {
            /** @var IReportsService */
            $scheduler = app()->make(IReportsService::class);
            Log::debug('report sent');
            $scheduler->batchReportMeasurements(1000);
        };
    }

    public function test() {
        return function () {
            dd(User::find(1));
        };
    }
}
