<?php

namespace Larapress\Reports\Services\LaravelEcho;

use Larapress\CRUD\Services\CRUD\ICRUDReportSource;
use Larapress\Reports\Services\Reports\ReportSourceTrait;
use Larapress\Reports\Services\Reports\IReportsService;
use Larapress\Reports\Services\Reports\ReportSourceProperties;

class LaravelEchoReports implements ICRUDReportSource
{
    use ReportSourceTrait;

    /** @var IReportsService */
    private $reports;

    /** @var array */
    private $avReports;

    public function __construct()
    {
        /** @var IReportsService */
        $this->reports = app(IReportsService::class);

        $this->avReports = [
            'website.online_users' => function ($user, array $options = []) {
                $props = ReportSourceProperties::fromReportSourceOptions($user, $options);
                return $this->reports->queryMeasurement(
                    'website.online_users',
                    $props->filters,
                    $props->groups,
                    array_merge(["_value", "_time"], $props->groups),
                    $props->from,
                    $props->to,
                    'aggregateWindow(every: '.$props->window.', fn: max)'
                );
            },
            'website.online_tabs' => function ($user, array $options = []) {
                $props = ReportSourceProperties::fromReportSourceOptions($user, $options);
                return $this->reports->queryMeasurement(
                    'website.online_tabs',
                    $props->filters,
                    $props->groups,
                    array_merge(["_value", "_time"], $props->groups),
                    $props->from,
                    $props->to,
                    'aggregateWindow(every: '.$props->window.', fn: max)'
                );
            }
        ];
    }
}
