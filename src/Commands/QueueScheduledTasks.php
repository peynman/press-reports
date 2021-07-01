<?php

namespace Larapress\Reports\Commands;

use Illuminate\Console\Command;
use Larapress\Reports\Services\TaskScheduler\ITaskSchedulerService;

class QueueScheduledTasks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:reports:queue-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue scheduled tasks in database.';

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
        /** @var ITaskReportService */
        $service = app(ITaskReportService::class);
        $service->queueScheduledTasks();

        $this->info("Done.");
        return 0;
    }
}
