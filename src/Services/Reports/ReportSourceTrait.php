<?php

namespace Larapress\Reports\Services\Reports;

use Illuminate\Database\Eloquent\Builder;
use Larapress\Profiles\IProfileUser;

trait ReportSourceTrait
{
    protected $avReports = [];

    /**
     * Undocumented function
     *
     * @param ICRUDUser $user
     *
     * @return string[]
     */
    public function getReportNames(IProfileUser $user): array
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
    public function getReport(IProfileUser $user, string $name, ReportQueryRequest $request): array
    {
        return $this->avReports[$name]($user, $request);
    }

    /**
     * Undocumented function
     *
     * @return IMetricsService
     */
    public function getMetricsService(): IMetricsService
    {
        return app(IMetricsService::class);
    }
}
