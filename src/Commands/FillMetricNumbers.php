<?php

namespace Larapress\Reports\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillMetricNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:reports:metric-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill metric numbers table';

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
        DB::table('metric_numbers')->truncate();

        for ($i = 0; $i < 300; $i++) {
            DB::table('metric_numbers')->insert([
                'num' => $i
            ]);
        }

        return 0;
    }
}
