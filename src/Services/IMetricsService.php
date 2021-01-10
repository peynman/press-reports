<?php

namespace Larapress\Reports\Services;

interface IMetricsService {
    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $key
     * @param float $value
     * @return void
     */
    public function pushMeasurement($domain_id, $group, $key, $value, $timestamp = null);

    /**
     * Undocumented function

     * @param string $key
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return int
     */
    public function sumMeasurement($key, array $domains = [], $from = null, $to = null): float;


    /**
     * Undocumented function
     *
     * @param string $key
     * @param array $groups
     * @param array $domains
     * @param array $filters
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public function aggregateMeasurementDotGrouped($key, array $filters = [], array $groups = [], array $domains = [], $from = null, $to = null);

    /**
     * Undocumented function
     *
     * @param string $key
     * @param array $filters
     * @param array $groups
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public function queryMeasurement($key, $window = 84600, array $filters, array $groups = [], array $domains = [], $from = null, $to = null);

    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     * @return int
     */
    public function removeMeasurement($domain_id, $group = null, $key = null);
}
