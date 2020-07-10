<?php

namespace Larapress\Reports\Services;

interface IMetricsService {
    public function pushMeasurement($domain, $key, $value);
}
