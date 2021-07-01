<?php

namespace Larapress\Reports\Services\TaskScheduler;

use Larapress\Reports\Models\TaskReport;

interface ITaskHandler
{
    /**
     * Undocumented function
     *
     * @param TaskReport $task
     * @return void
     */
    public function handle(TaskReport $task);
}
