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
     * @return float
     */
    public function sumMeasurement($key, array $domains = [], $from = null, $to = null): float;

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
