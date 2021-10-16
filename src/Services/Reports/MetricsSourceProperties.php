<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;
use DateInterval;
use Larapress\Profiles\IProfileUser;

class MetricsSourceProperties {
    /** @var Carbon */
    public $from;
    /** @var Carbon */
    public $to;
    /** @var array */
    public $filters;
    /** @var array */
    public $groupBy;
    /** @var string */
    public $window;
    /** @var array */
    public $domains;

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @param array $options
     *
     * @return MetricsSourceProperties
     */
    public static function fromReportSourceOptions(IProfileUser $user, ReportQueryRequest $request) {
        $filters = [];

        $domains = [];
        if (isset($filters['domain'])) {
            $domains = $filters['domain'];
            unset($filters['domain']);
        }

        if (isset($options['from'])) {
            $fromC = Carbon::parse($options['from'])->utc();
        } else {
            $fromC = Carbon::now()->addHour(-6);
        }
        if (isset($options['to'])) {
            $toC = Carbon::parse($options['to'])->utc();
        } else {
            $toC = Carbon::now();
        }

        $window = isset($options['window']) ? $options['window'] : 3600;
        if (is_string($window)) {
            function intervalToSeconds(\DateInterval $interval) {
                return $interval->y * (86400*365) + $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;
            }
            $window = 'PT'.strtoupper($window);
            $window = new DateInterval($window);
            $window = intervalToSeconds($window);
        }

        $props = new MetricsSourceProperties();
        $props->from = $fromC;
        $props->to = $toC;
        $props->filters = $filters;
        $props->groups = $groups;
        $props->window = $window;
        $props->domains = $domains;

        return $props;
    }
}
