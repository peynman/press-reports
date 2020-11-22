<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\BaseCRUDProvider;
use Larapress\CRUD\Services\ICRUDProvider;
use Larapress\CRUD\Services\IPermissionsMetadata;
use Larapress\Reports\Models\TaskReport;

class TaskReportsCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.task_reports.name';
    public $verbs = [
        self::VIEW,
        'queue',
    ];
    public $model = TaskReport::class;
    public $validRelations = [
    ];
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
    public $filterFields = [
        'created_from' => 'after:created_at',
        'created_to' => 'before:created_at',
    ];
}
