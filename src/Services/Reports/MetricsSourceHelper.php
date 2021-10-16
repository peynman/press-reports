<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Larapress\Reports\Services\Reports\ReportQueryRequest;

trait MetricsSourceHelper
{
    /**
     * Undocumented function
     *
     * @param Builder $query
     * @param string $data
     * @param mixed $value
     *
     * @return Builder
     */
    public function filterWhereData(Builder $query, string $data, string $operator, mixed $value): Builder
    {
        $query->where("data->$data", $operator, $value);
        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Builder $query
     * @param string $data
     * @param mixed $value
     *
     * @return Builder
     */
    public function filterWhereDataIn(Builder $query, string $data, mixed $value): Builder
    {
        $query->whereIn("data->$data", $value);
        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Builder $query
     * @param ReportQueryRequest $request
     *
     * @return Builder
     */
    public function appendGroupByUserGroup(Builder $query): Builder
    {
        $query->addSelect(DB::raw('metric_counter_group_pivot.group_id as group_id'));
        $query->rightJoin('metric_counter_group_pivot', function (JoinClause $j) {
            $j->on('metric_counter_group_pivot.metric_id', '=', 'metric_counters.id');
        }, 'outer');
        $query->groupBy('group_id');

        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Builder $query
     * @return Builder
     */
    public function appendGroupByDomain(Builder $query): Builder
    {
        $query->addSelect('domain_id');
        $query->groupBy('domain_id');
        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Builder $query
     * @return Builder
     */
    public function appendGroupByUser(Builder $query): Builder
    {
        $query->addSelect('user_id');
        $query->groupBy('user_id');
        return $query;
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @param ReportQueryRequest $request
     *
     * @return array
     */
    public function appendMissingDates(array $data, ReportQueryRequest $request): array
    {
        if (!$request->shouldAggregate() || count($data) === 0) {
            return $data;
        }

        $end = $request->getTo() ?? Carbon::now();
        $window = $request->getAggregateWindow();

        $filled = [];

        $fisrt = Carbon::createFromTimestamp($data[0]['_time']);

        $groups = $request->getGroupBy();

        return $filled;
    }
}
