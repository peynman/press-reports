<?php

namespace Larapress\Reports\Services;

interface ITaskReportService {
    /**
     * @param string   $type
     * @param string   $name
     * @param callable $callback
     *
     * @return mixed
     */
    public function makeSyncronizedTaskReport($type, $name, $callback);
}
