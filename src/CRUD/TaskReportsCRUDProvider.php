<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Base\BaseCRUDProvider;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\CRUD\Base\IPermissionsMetadata;
use Larapress\Reports\Models\TaskReport;

class TaskReportsCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.task_reports.name';
    public $verbs = [
        self::VIEW,
        self::DELETE,
    ];
    public $model = TaskReport::class;
    public $validRelations = [
        'author',
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
}
