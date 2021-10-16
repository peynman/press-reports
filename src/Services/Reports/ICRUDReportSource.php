<?php

namespace Larapress\Reports\Services\Reports;

use Larapress\CRUD\ICRUDUser;

interface ICRUDReportSource
{
    /**
     * Undocumented function
     *
     * @return string
     */
    public function name(): string;

    /**
     * Undocumented function
     *
     * @param ICRUDUser $user
     * @param ReportQueryRequest $request
     *
     * @return array
     */
    public function getReport(ICRUDUser $user, ReportQueryRequest $request): array;
}
