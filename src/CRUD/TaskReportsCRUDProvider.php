<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Base\BaseCRUDProvider;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\Reports\Models\TaskReport;

class TaskReportsCRUDProvider implements ICRUDProvider
{
    use BaseCRUDProvider;

    public $model = TaskReport::class;
    public $createValidations = [
    ];
    public $updateValidations = [
    ];
    public $autoSyncRelations = [];
    public $validSortColumns = [
        'id',
        'key',
        'type',
        'status',
        'created_at',
        'updated_at',
    ];
    public $validRelations = [];
    public $validFilters = [];
    public $defaultShowRelations = [];
    public $excludeFromUpdate = [];
    public $searchColumns = [];
    public $filterDefaults = [];
    public $filterFields = [];
}
