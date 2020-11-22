<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\BaseCRUDProvider;
use Larapress\CRUD\Services\ICRUDProvider;
use Larapress\CRUD\Services\IPermissionsMetadata;
use Larapress\Reports\Models\MetricCounter;

class MetricsCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.metrics.name';
    public $verbs = [
        self::VIEW
    ];
    public $model = MetricCounter::class;
    public $validRelations = [
        'domain',
    ];
    public $validSortColumns = [
        'id',
        'key',
        'group',
        'value',
        'domain_id',
        'created_at',
    ];
    public $filterFields = [
        'from' => 'after:created_at',
        'to' => 'before:created_at',
        'group' => 'equals:group',
        'key' => 'like:key',
    ];
    public $filterDefaults = [];
}
