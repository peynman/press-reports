<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Larapress\Reports\Models\MetricCounter;
use Larapress\CRUD\Events\CRUDCreated;
use Illuminate\Database\Query\Builder;

class MetricsService implements IMetricsService
{
    /**
     * Undocumented function
     *
     * @param [type] $domain_id
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public function pushMeasurement($domain_id, $group, $key, $value, $timestamp = null)
    {
        return MetricCounter::updateOrCreate([
            'key' => $key,
            'group' => $group,
        ], [
            'domain_id' => $domain_id,
            'value' => floatval($value),
            'created_at' => is_null($timestamp) ? Carbon::now()->toDateTimeString() : $timestamp->toDateTimeString()
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return float
     */
    public function sumMeasurement($key, array $domains = [], $from = null, $to = null): float
    {
        /** @var Builder $query */
        $query = MetricCounter::query();

        if (str_contains($key, "%")) {
            $query->where('key', 'LIKE', $key);
        } else {
            $query->where('key', $key);
        }

        if (count($domains) > 0) {
            $query->whereIn('domain_id', $domains);
        }

        if (!is_null($from) && !is_null($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        } else {
            if (!is_null($from)) {
                $query->where('created_at', '>=', $from);
            }
            if (!is_null($to)) {
                $query->where('created_at', '<=', $to);
            }
        }

        return $query->sum('value');
    }

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
    public function aggregateMeasurementDotGrouped($key, array $filters = [], array $groups = [], array $domains = [], $from = null, $to = null)
    {
        $selects = [DB::raw('sum(value) as _value')];

        foreach ($groups as $groupname => $groupIndex) {
            if (is_string($groupname) && is_numeric($groupIndex)) {
                $selects[] = DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(`key`, '.', $groupIndex), '.', -1) as $groupname");
            }
        }

        /** @var Builder $query */
        $query = MetricCounter::query()->select($selects);

        if (str_contains($key, "%")) {
            $query->where('key', 'LIKE', $key);
        } else {
            $query->where('key', $key);
        }

        if (count($domains) > 0) {
            $query->whereIn('domain_id', $domains);
        }

        if (!is_null($from) && !is_null($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        } else {
            if (!is_null($from)) {
                $query->where('created_at', '>=', $from);
            }
            if (!is_null($to)) {
                $query->where('created_at', '<=', $to);
            }
        }

        if (count($filters) > 0) {
            foreach ($filters as $filterName => $filterValue) {
                $query->having($filterName, $filterValue);
            }
        }

        if (!is_null($groups) && count($groups) > 0) {
            $query->groupBy(array_keys($groups));
        }

        return $query->get();
    }


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
    public function queryMeasurement($key, $window = 84600, array $filters, array $groups = [], array $domains = [], $from = null, $to = null)
    {
        $selects = [DB::raw("sum(value) as _value"), DB::raw("FLOOR(UNIX_TIMESTAMP(created_at)/$window)*$window as _time")];

        foreach ($groups as $groupname => $groupIndex) {
            if (is_string($groupname) && is_numeric($groupIndex)) {
                $selects[] = DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(`key`, '.', $groupIndex), '.', -1) as $groupname");
            }
        }

        /** @var Builder $query */
        $query = MetricCounter::query()->select($selects);

        if (str_contains($key, "%")) {
            $query->where('key', 'LIKE', $key);
        } else {
            $query->where('key', $key);
        }

        if (count($domains) > 0) {
            $query->whereIn('domain_id', $domains);
        }

        if (!is_null($from) && !is_null($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        } else {
            if (!is_null($from)) {
                $query->where('created_at', '>=', $from);
            }
            if (!is_null($to)) {
                $query->where('created_at', '<=', $to);
            }
        }

        if (count($filters) > 0) {
            foreach ($filters as $filterName => $filterValue) {
                $query->having($filterName, $filterValue);
            }
        }

        $groupedCols = [
            '_time'
        ];
        if (!is_null($groups) && count($groups) > 0) {
            $groupedCols = array_merge($groupedCols, array_keys($groups));
        }
        $query->groupBy($groupedCols);

        $query->orderBy('_time', 'desc');

        /** @var  */
        $measurements = $query->get();
        return $measurements;
    }

    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     * @return int
     */
    public function removeMeasurement($domain_id, $group = null, $key = null)
    {
        $query = MetricCounter::query()->where('domain_id', $domain_id);
        if (!is_null($group)) {
            $query->where('group', $group);
        }
        if (!is_null($key)) {
            $query->where('group', $key);
        }

        return $query->delete();
    }
}
