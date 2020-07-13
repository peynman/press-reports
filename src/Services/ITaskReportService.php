<?php

namespace Larapress\Reports\Services;

interface ITaskReportService {
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
     * Undocumented function
     *
     * @param string $type
     * @param string $name
     * @param string $desc
     * @param array $data
     * @return void
     */
    public function scheduleTask(string $type, string $name, string $desc, array $data, $autoStart = false);
}
