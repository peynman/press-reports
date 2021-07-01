<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\CRUD\Traits\CRUDProviderTrait;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDVerb;
use Larapress\CRUD\Services\RBAC\IPermissionsMetadata;

class MetricsCRUDProvider implements ICRUDProvider
{
    use CRUDProviderTrait;

    public $name_in_config = 'larapress.reports.routes.metrics.name';
    public $model_in_config = 'larapress.reports.routes.metrics.model';
    public $compositions_in_config = 'larapress.reports.routes.metrics.compositions';

    public $verbs = [
        ICRUDVerb::VIEW
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
