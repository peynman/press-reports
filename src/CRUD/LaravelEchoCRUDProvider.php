<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\CRUD\BaseCRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\RBAC\IPermissionsMetadata;
use Larapress\Reports\Services\IReportsService;
use Larapress\Reports\Services\LaravelEcho\LaravelEchoReports;

class LaravelEchoCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.laravel_echo.name';
    public $extend_in_config = 'larapress.reports.routes.laravel_echo.extend.providers';
    public $verbs = [
        self::REPORTS
    ];

    /**
     *
     */
    public function getReportSources()
    {
        /** @var IReportsService */
        $service = app(IReportsService::class);
        return [
            new LaravelEchoReports($service),
        ];
    }
}
