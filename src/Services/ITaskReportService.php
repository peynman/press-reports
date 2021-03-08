<?php

namespace Larapress\Reports\Services;

interface ITaskReportService
{
    /**
     * @param string $type
     * @param string $name
     * @param string $desc
     * @param array $data
     * @param callable $callback
     *
     * @return mixed
     */
    public function startSyncronizedTaskReport(string $type, string $name, string $desc, array $data, $callback);

    /**
     * schedule a task;
     *  if autostart is true => task will be picked up on next queue command run [larapress::reports --action=tasks:queue]
     *  if a timestamp is provided, the task will not be picked up until this time is passed
     *
     * @param string $type
     * @param string $name
     * @param string $desc
     * @param array $data
     * @param bool|string $desc
     * @return void
     */
    public function scheduleTask(string $type, string $name, string $desc, array $data, $autoStart = false);

    /**
     * Undocumented function
     * @return TaskReport[]
     */
    public function queueScheduledTasks();

    /**
     * Undocumented function
     *
     * @param int $id
     * @return TaskReport
     */
    public function queueScheduledTask($id);
}
