<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\CRUD\Traits\CRUDProviderTrait;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDVerb;
use Larapress\CRUD\Services\RBAC\IPermissionsMetadata;
use Larapress\Reports\Services\LaravelEcho\LaravelEchoReports;

class LaravelEchoCRUDProvider implements ICRUDProvider
{
    use CRUDProviderTrait;

    public $name_in_config = 'larapress.reports.routes.laravel_echo.name';
    public $compositions_in_config = 'larapress.reports.routes.laravel_echo.compositions';
    public $verbs = [
        ICRUDVerb::REPORTS
    ];

    /**
     *
     */
    public function getReportSources(): array
    {
        return [
            new LaravelEchoReports(),
        ];
    }
}
