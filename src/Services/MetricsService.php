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
    public function pushMeasurement($domain_id, $key, $value, $timestamp = null) {
        MetricCounter::create([
            'domain_id' => $domain_id,
            'key' => $key,
            'value' => $value,
            'created_at' => is_null($timestamp) ? Carbon::now() : $timestamp
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
    public function sumMeasurement($key, array $domains = [], $from = null, $to = null) {
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
}
