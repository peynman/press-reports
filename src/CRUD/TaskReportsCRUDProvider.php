<?php


namespace Larapress\Reports\CRUD;

use Larapress\CRUD\Services\CRUD\Traits\CRUDProviderTrait;
use Larapress\CRUD\Services\CRUD\ICRUDProvider;
use Larapress\CRUD\Services\CRUD\ICRUDVerb;
use Larapress\Reports\Controllers\TaskReportController;

class TaskReportsCRUDProvider implements ICRUDProvider
{
    use CRUDProviderTrait;

    public $name_in_config = 'larapress.reports.routes.task_reports.name';
    public $model_in_config = 'larapress.reports.routes.task_reports.model';
    public $compositions_in_config = 'larapress.reports.routes.metrics.compositions';

    public $validSortColumns = [
        'id',
        'name',
        'type',
        'status',
        'author_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'started_at',
        'stopped_at',
    ];
    public $filterFields = [
        'created_from' => 'after:created_at',
        'created_to' => 'before:created_at',
    ];

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getPermissionVerbs(): array
    {
        return [
            ICRUDVerb::VIEW,
            ICRUDVerb::SHOW,
            'queue' => [
                'uses' => '\\' . TaskReportController::class . '@queueTask',
                'methods' => ['POST'],
                'url' => config('larapress.reports.routes.task_reports.name') . '/queue/{id}',
            ]
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getValidRelations(): array
    {
        return [
            'author' => config('larapress.crud.user.provider'),
        ];
    }
}
