<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Larapress\Reports\Models\MetricCounter;
use Illuminate\Database\Query\Builder;

class MetricsService implements IMetricsService
{
    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $key
     * @param float $value
     * @param Carbon|int|null $timestamp
     *
     * @return void
     */
    public function pushMeasurement(int $domain_id, string $type, string $group, string $key, float $value, $timestamp = null)
    {
        if (is_null($timestamp)) {
            $timestamp = Carbon::now();
        } else if (is_numeric($timestamp)) {
            $timestamp = Carbon::createFromTimestamp($timestamp);
        }

        return MetricCounter::create([
            'key' => $key,
            'group' => $group,
            'type' => $type,
            'domain_id' => $domain_id,
            'value' => floatval($value),
            'created_at' => $timestamp->toDateTimeString()
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param string|null $group
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return float
     */
    public function sumMeasurement(string $key, $type, $group, array $domains = [], $from = null, $to = null): float
    {
        /** @var Builder $query */
        $query = MetricCounter::query();

        if (str_contains($key, "%")) {
            $query->where('key', 'LIKE', $key);
        } else {
            $query->where('key', $key);
        }
        if (!is_null($group)) {
            $query->where('group', $group);
        }

        if (!is_null($domains) && is_array($domains) && count($domains) > 0) {
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
     * @param string|null $group
     * @param array $groups
     * @param array $domains
     * @param array $filters
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public function queryMeasurement(string $key, $type, $group, array $filters = [], array $groups = [], array $domains = [], $from = null, $to = null)
    {
        $selects = [DB::raw('sum(value) as _value')];

        foreach ($groups as $groupname => $groupIndex) {
            if (is_string($groupname) && is_numeric($groupIndex) && !is_null($groupIndex)) {
                $selects[] = DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(`key`, '.', $groupIndex), '.', -1) as $groupname");
            } else if (is_string($groupname) && is_string($groupIndex)) {
                $selects[] = "$groupname as $groupIndex";
            }
        }

        /** @var Builder $query */
        $query = MetricCounter::query()->select($selects);

        $query->where('key', 'RLIKE', $key);
        if (!is_null($group)) {
            $query->where('group', $group);
        }
        if (!is_null($type)) {
            $query->where('type', $type);
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
     * @param string|null $group
     * @param array $filters
     * @param array $groups
     * @param array $domains
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public function aggregateMeasurement(string $key, $type, $group, array $filters, array $groups = [], array $domains = [], $from = null, $to = null, $window = 84600)
    {
        if ($window === 0) {
            $window = 3600;
        }
        $selects = [DB::raw("sum(value) as _value"), DB::raw("(FLOOR(UNIX_TIMESTAMP(created_at)/$window)*$window) * 1000 as _time")];

        foreach ($groups as $groupname => $groupIndex) {
            if (is_string($groupname) && is_numeric($groupIndex) && !is_null($groupIndex)) {
                $selects[] = DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(`key`, '.', $groupIndex), '.', -1) as $groupname");
            } else if (is_string($groupname) && is_string($groupIndex)) {
                $selects[] = "$groupname as $groupIndex";
            }
        }

        /** @var Builder $query */
        $query = MetricCounter::query()->select($selects);

        $query->where('key', 'RLIKE', $key);
        if (!is_null($group)) {
            $query->where('group', $group);
        }
        if (!is_null($type)) {
            $query->where('type', $type);
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
    public function removeMeasurement($domain_id, string $type, string $group, $key = null)
    {
        $query = MetricCounter::query()
            ->where('domain_id', $domain_id)
            ->where('type', $type)
            ->where('group', $group);

        if (!is_null($key)) {
            $query->where('group', $key);
        }

        return $query->delete();
    }
}
