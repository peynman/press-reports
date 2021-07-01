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
    public $groups;
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
    public static function fromReportSourceOptions(IProfileUser $user, array $options, array $dotGroupsPositions = []) {
        $filters = [];

        $providers = config('larapress.reports.common_filters');
        if (!is_null($providers) && count($providers)) {
            foreach ($providers as $providerClass) {
                if (is_string($providerClass)) {
                    /** @var IReportsServciceProvider $provider */
                    $provider = new $providerClass();
                    $filters = array_merge($filters, $provider->getFiltersForReports($user, $options));
                }
            }
        }

        $domains = [];
        if (isset($filters['domain'])) {
            $domains = $filters['domain'];
            unset($filters['domain']);
        }

        $groups = [];
        $avDotGroups = array_keys($dotGroupsPositions);
        if (isset($options['filters'])) {
            foreach ($options['filters'] as $filter => $value) {
                if (in_array($filter, $avDotGroups)) {
                    $groups[$filter] = $dotGroupsPositions[$filter];
                    $filters[$filter] = $value;
                }
            }
        }

        if (isset($options['groups'])) {
            if (is_string($options['groups'])) {
                if (isset($avDotGroups[$options['groups']])) {
                    $groups[$options['groups']] = $avDotGroups[$options['groups']];
                }
            } else if (is_array($options['groups'])) {
                foreach ($options['groups'] as $group) {
                    if (is_string($group)) {
                        if (isset($dotGroupsPositions[$group])) {
                            $groups[$dotGroupsPositions[$group]] = $group;
                        }
                    }
                }
            }
        }

        $fromC = Carbon::now()->addHour(-6);
        $toC = Carbon::now();
        if (isset($options['from'])) {
            $fromC = Carbon::parse($options['from'])->utc();
        }
        if (isset($options['to'])) {
            $toC = Carbon::parse($options['to'])->utc();
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
