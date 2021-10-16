<?php

namespace Larapress\Reports\Services\Reports;

use Larapress\Reports\Models\MetricCounter;
use Illuminate\Database\Eloquent\Builder;
use Larapress\Profiles\IProfileUser;

interface IMetricsService
{
    /**
     * Undocumented function
     *
     * @param integer $domain_id
     * @param integer|null $user_id
     * @param integer|null $product_id
     * @param array|null $group_ids
     * @param integer $type
     * @param string $group
     * @param string $key
     * @param float $value
     * @param array|null $data
     * @param Carbon|null|int $timestamp
     *
     * @return MetricCounter
     */
    public function pushMeasurement(
        int $domain_id,
        int|null $user_id,
        int|null $product_id,
        array|null $group_ids,
        int $type,
        string $group,
        string $key,
        float $value,
        array|null $data,
        $timestamp = null
    );


    /**
     * Undocumented function
     *
     * @param integer $domain_id
     * @param integer|null $user_id
     * @param integer|null $product_id
     * @param array|null $group_ids
     * @param integer $type
     * @param string $group
     * @param string $key
     * @param float $value
     * @param array|null $data
     * @param Carbon|null|int $timestamp
     *
     * @return MetricCounter
     */
    public function updateMeasurement(
        int $domain_id,
        int|null $user_id,
        int|null $product_id,
        array|null $group_ids,
        int $type,
        string $group,
        string $key,
        float $value,
        array|null $data,
        $timestamp = null
    );

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @param ReportQueryRequest $request
     * @param string $group
     * @param integer $type
     * @param string|null $func
     * @param integer|null $window
     * @return Builder
     */
    public function measurementQuery(
        IProfileUser $user,
        ReportQueryRequest $request,
        string $group,
        int $type,
        ?string $func = null,
        ?int $window = null,
    ): Builder;

    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     *
     * @return int
     */
    public function removeMeasurement($domain_id, int $type, string|null $group, $key = null);
}
