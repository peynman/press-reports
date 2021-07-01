<?php

namespace Larapress\Reports\Services\Reports;

interface IMetricsService
{
    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $type
     * @param string $group
     * @param string $key
     * @param float $value
     * @param Carbon|int|null $timestamp
     *
     * @return void
     */
    public function pushMeasurement(int $domain_id, string $type, string $group, string $key, float $value, $timestamp = null);

    /**
     * Undocumented function

     * @param string $key
     * @param string|null $group
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     *
     * @return float
     */
    public function sumMeasurement(string $key, $type, $group, array $domains = [], $from = null, $to = null): float;


    /**
     * Undocumented function
     *
     * @param string $key
     * @param string|null $group
     * @param array $filters
     * @param array $groups
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     *
     * @return array
     */
    public function queryMeasurement(string $key, $type, $group, array $filters = [], array $groups = [], array $domains = [], $from = null, $to = null);

    /**
     * Undocumented function
     *
     * @param string $key
     * @param string|null $group
     * @param array $filters
     * @param array $groups
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @param int $window
     *
     * @return array
     */
    public function aggregateMeasurement(string $key, $type, $group, array $filters, array $groups = [], array $domains = [], $from = null, $to = null, $window = 84600);

    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     *
     * @return int
     */
    public function removeMeasurement($domain_id, string $type, string $group, $key = null);
}
