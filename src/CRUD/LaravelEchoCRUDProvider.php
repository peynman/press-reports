<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\BaseCRUDProvider;
use Larapress\CRUD\Services\ICRUDProvider;
use Larapress\CRUD\Services\IPermissionsMetadata;
use Larapress\Reports\Services\IReportsService;
use Larapress\Reports\Services\LaravelEcho\LaravelEchoReports;

class LaravelEchoCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.laravel_echo.name';
    public $verbs = [
        self::REPORTS
    ];
    public $model = null;
    public $validRelations = [
    ];
    public $validSortColumns = [
    ];
    public $filterFields = [
    ];
    public $filterDefaults = [];


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
