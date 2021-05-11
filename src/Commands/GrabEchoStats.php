<?php

namespace Larapress\Reports\Commands;

use Illuminate\Console\Command;
use Larapress\Reports\Services\LaravelEcho\ILaravelEchoMetrics;

class GrabEchoStats extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:reports:grab-echo-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab echo statistics from Echo api endpoint';

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
        /** @var ILaravelEchoMetrics */
        $service = app(ILaravelEchoMetrics::class);
        $service->pushEchoMeasurements();

        $this->info("Done.");
        return 0;
    }
}
