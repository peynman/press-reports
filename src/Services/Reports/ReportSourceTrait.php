<?php

namespace Larapress\Reports\Services\Reports;

use Larapress\CRUD\ICRUDUser;

trait ReportSourceTrait
{
    /**
     * Undocumented function
     *
     * @param ICRUDUser $user
     *
     * @return string[]
     */
    public function getReportNames(ICRUDUser $user)
    {
        return array_keys($this->avReports);
    }

    /**
     * grab data from database and present it
     *
     * @param ICRUDUser $user
     *
     * @return array
     */
    public function getReport(ICRUDUser $user, string $name, array $options = [])
    {
        return $this->avReports[$name]($user, $options);
    }
}
