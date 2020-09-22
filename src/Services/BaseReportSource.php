<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;

trait BaseReportSource {

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
     * @param IProfileUser|ICRUDUser $user
     * @return void
     */
    public function getReport($user, string $name, array $options = [])
    {
        return $this->avReports[$name]($user, $options);
    }

    public function getCommonReportProps($user, $options) {
        $filters = [];
        if (!$user->hasRole(config('larapress.profiles.security.roles.super-role'))) {
            $filters['domain'] = $user->getAffiliateDomainIds();
        }
        if ($options['filters']) {
            foreach($options['filters'] as $filter => $values) {
                if (!is_array($values) || count($values) > 0) {
                    $filters[$filter] = $values;
                }
            }
        }
        $fromC = Carbon::now()->addHour(-6);
        $toC = Carbon::now();
        if (isset($options['from'])) {
            $fromC = Carbon::createFromFormat(config('larapress.crud.datetime-format'), $options['from'])->utc();
        }
        if (isset($options['to'])) {
            $toC = Carbon::createFromFormat(config('larapress.crud.datetime-format'), $options['to'])->utc();
        }
        if (isset($options['filters'])) {
            $filters = array_merge($filters, $options['filters']);
        }
        $groups = isset($options['group']) ? $options['group'] : [];



        return [$filters, $fromC, $toC, $groups];
    }
}
