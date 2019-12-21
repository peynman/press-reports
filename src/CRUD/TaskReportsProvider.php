<?php


namespace Larapress\Dashboard\CRUD;

use App\Models\TaskReport;
use Larapress\CRUD\Base\BaseCRUDProvider;
use Larapress\CRUD\Base\ICRUDProvider;

class TaskReportsProvider implements ICRUDProvider
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
