<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;
use Larapress\Reports\Models\MetricCounter;
use Larapress\CRUD\Events\CRUDCreated;

class MetricsService implements IMetricsService {
    /**
     * Undocumented function
     *
     * @param [type] $domain_id
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public function pushMeasurement($domain_id, $group, $key, $value, $timestamp = null) {
        return MetricCounter::updateOrCreate([
            'key' => $key,
            'group' => $group,
        ],[
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
    public function sumMeasurement($key, array $domains = [], $from = null, $to = null) : float {
        $query = MetricCounter::query()->where('key', $key);

        if (count($domains) > 0) {
            $query->whereIn('domain_id', $domains);
        }

        if (!is_null($from)) {
            $query->where('created_at', '>=', $from);
        }

        if (!is_null($to)) {
            $query->where('created_at', '<=', $to);
        }

        return $query->sum('value');
    }


    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     * @return int
     */
    public function removeMeasurement($domain_id, $group = null, $key = null) {
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
