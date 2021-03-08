<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;

/**
 * Undocumented interface
 */
interface IReportsService
{
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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function barchReportPurge();

    /**
     * Undocumented function
     *
     * @param String $name
     * @param array $filters
     * @param array $groups
     * @param array $columns
     * @param Carbon $from
     * @param [type] $to
     * @param [type] $function
     * @return array
     */
    public function queryMeasurement(String $name, array $filters, array $groups, array $columns, Carbon $from, $to, $function);

    /**
     * Undocumented function
     *
     * @param String $name
     * @param array $filters
     * @return void
     */
    public function removeMeasurement(String $name, array $filters);
}
