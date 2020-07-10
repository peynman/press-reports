<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Base\BaseCRUDProvider;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\CRUD\Base\IPermissionsMetadata;
use Larapress\Reports\Models\TaskReport;

class TaskReportsCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.task-reports.name';
    public $verbs = [
        self::VIEW,
        self::DELETE,
    ];
    public $model = TaskReport::class;
    public $createValidations = [
    ];
    public $updateValidations = [
    ];
    public $autoSyncRelations = [];
    public $validSortColumns = [
        'id',
        'name',
        'type',
        'status',
        'created_at',
        'updated_at',
        'started_at',
        'stopped_at',
    ];
    public $validRelations = [];
    public $validFilters = [];
    public $defaultShowRelations = [];
    public $excludeIfNull = [];
    public $searchColumns = [];
    public $filterDefaults = [];
    public $filterFields = [];
}
