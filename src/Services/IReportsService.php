<?php

namespace Larapress\Reports\Services;

/**
 * Undocumented interface
 */
interface IReportsService {
    /**
     * Undocumented function
     *
     * @param String $name
     * @param integer $value
     * @param array $tags
     * @param array $fields
     * @param integer $timestamp
     * @return bool
     */
    public function pushMeasurement(String $name, int $value, array $tags, array $fields, int $timestamp);

    /**
     * Undocumented function
     *
     * @param int $max
     * @return void
     */
    public function batchReportMeasurements(int $max);
}
