<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\CRUD\BaseCRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\RBAC\IPermissionsMetadata;

class MetricsCRUDProvider implements ICRUDProvider, IPermissionsMetadata
{
    use BaseCRUDProvider;

    public $name_in_config = 'larapress.reports.routes.metrics.name';
    public $class_in_config = 'larapress.reports.routes.metrics.model';
    public $extend_in_config = 'larapress.reports.routes.metrics.extend.providers';
    public $verbs = [
        self::VIEW
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
        'created_from' => 'after:created_at',
        'created_to' => 'before:created_at',
        'group' => 'equals:group',
        'key' => 'like:key',
    ];
}
