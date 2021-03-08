<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;
use Larapress\CRUD\ICRUDUser;

trait BaseReportSource
{

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return void
     */
    public function getReportNames($user)
    {
        return array_keys($this->avReports);
    }

    /**
     * grab data from database and present it
     *
     * @param ICRUDUser $user
     * @return void
     */
    public function getReport($user, string $name, array $options = [])
    {
        return $this->avReports[$name]($user, $options);
    }

    /**
     * Undocumented function
     *
     * @param ICRUDUser $user
     * @param array $options
     * @return [array $filters, Carbon $from, Carbon $to, array $groups]
     */
    public function getCommonReportProps($user, $options)
    {
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

        return [$filters, $fromC, $toC, $groups];
    }
}
