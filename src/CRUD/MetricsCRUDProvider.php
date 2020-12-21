<?php


namespace Larapress\Reports\CRUD;

use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Services\BaseCRUDProvider;
use Larapress\CRUD\Services\ICRUDProvider;
use Larapress\CRUD\Services\IPermissionsMetadata;
use Larapress\Profiles\Models\FormEntry;
use Larapress\Reports\Models\MetricCounter;
use Larapress\ECommerce\IECommerceUser;

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


    /**
     * @param Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function onBeforeQuery($query)
    {
        /** @var IECommerceUser $user */
        $user = Auth::user();
        if (! $user->hasRole(config('larapress.profiles.security.roles.super-role'))) {
            if ($user->hasRole(config('larapress.ecommerce.lms.owner_role_id'))) {
                $ownerEntries = collect($user->getOwenedProductsIds())->map(function ($id) {
                    return 'product[.]'.$id.'[.].*';;
                })->toArray();
                $query->whereRaw('metrics_counters.key REGEXP \''.implode('|', $ownerEntries).'\'');
            } else if ($user->hasRole(config('larapress.ecommerce.lms.support_role_id'))) {
                $query->where('key', 'LIKE', '%.'.$user->id);
            } else {
                $query->whereHas('domains', function ($q) use ($user) {
                    $q->whereIn('id', $user->getAffiliateDomainIds());
                });
            }
        }

        return $query;
    }
}
