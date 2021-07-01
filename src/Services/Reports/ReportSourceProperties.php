<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;

use Larapress\Profiles\IProfileUser;

class ReportSourceProperties {
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

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @param array $options
     *
     * @return ReportSourceProperties
     */
    public static function fromReportSourceOptions(IProfileUser $user, array $options) {
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

        if (isset($options['filters'])) {
            foreach ($options['filters'] as $filter => $values) {
                if (!is_array($values) || count($values) > 0) {
                    $filters[$filter] = $values;
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

        $groups = isset($options['group']) ? $options['group'] : [];
        $window = isset($options['window']) ? $options['window'] : '1h';

        $props = new ReportSourceProperties();
        $props->from = $fromC;
        $props->to = $toC;
        $props->filters = $filters;
        $props->groups = $groups;
        $props->window = $window;

        return $props;
    }
}
