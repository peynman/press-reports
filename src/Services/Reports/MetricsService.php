<?php

namespace Larapress\Reports\Services\Reports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Larapress\Profiles\IProfileUser;
use Larapress\Reports\Models\MetricCounter;

class MetricsService implements IMetricsService
{
    /** @var Builder */
    public $query = null;

    /**
     * Undocumented function
     *
     * @param integer $domain_id
     * @param integer|null $user_id
     * @param integer|null $product_id
     * @param array|null $group_ids
     * @param integer $type
     * @param string $group
     * @param string $key
     * @param float $value
     * @param array|null $data
     * @param Carbon|null|int $timestamp
     *
     * @return MetricCounter
     */
    public function pushMeasurement(
        int $domain_id,
        int|null $user_id,
        int|null $product_id,
        array|null $group_ids,
        int $type,
        string $group,
        string $key,
        float $value,
        array|null $data,
        $timestamp = null
    ) {
        if (is_null($timestamp)) {
            $timestamp = Carbon::now();
        } else if (is_numeric($timestamp)) {
            $timestamp = Carbon::createFromTimestamp($timestamp);
        }

        /** @var MetricCounter */
        $m = MetricCounter::create([
            'key' => $key,
            'group' => $group,
            'type' => $type,
            'domain_id' => $domain_id,
            'user_id' => $user_id,
            'product_id' => $product_id,
            'value' => floatval($value),
            'data' => $data,
            'created_at' => $timestamp->toDateTimeString()
        ]);

        if (!is_null($group_ids)) {
            $m->groups()->sync($group_ids);
        }

        return $m;
    }

    /**
     * Undocumented function
     *
     * @param integer $domain_id
     * @param integer|null $user_id
     * @param integer|null $product_id
     * @param array|null $group_ids
     * @param integer $type
     * @param string $group
     * @param string $key
     * @param float $value
     * @param array|null $data
     * @param Carbon|null|int $timestamp
     *
     * @return MetricCounter
     */
    public function updateMeasurement(
        int $domain_id,
        int|null $user_id,
        int|null $product_id,
        array|null $group_ids,
        int $type,
        string $group,
        string $key,
        float $value,
        array|null $data,
        $timestamp = null
    ) {
        if (is_null($timestamp)) {
            $timestamp = Carbon::now();
        } else if (is_numeric($timestamp)) {
            $timestamp = Carbon::createFromTimestamp($timestamp);
        }

        /** @var MetricCounter */
        $m = MetricCounter::updateOrCreate([
            'key' => $key,
            'group' => $group,
            'type' => $type,
            'domain_id' => $domain_id,
            'user_id' => $user_id,
            'product_id' => $product_id,
        ], [
            'value' => floatval($value),
            'data' => $data,
            'created_at' => $timestamp->toDateTimeString()
        ]);

        if (!is_null($group_ids)) {
            $m->groups()->sync($group_ids);
        }

        return $m;
    }

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @param ReportQueryRequest $request
     * @param string $group
     * @param integer $type
     * @param string|null $func
     * @param integer|null $window
     * @return Builder
     */
    public function measurementQuery(
        IProfileUser $user,
        ReportQueryRequest $request,
        string $group,
        int $type,
        ?string $func = null,
        ?int $window = null,
    ): Builder {

        /** @var Builder $query */
        $query = MetricCounter::query()
            ->where('group', $group)
            ->where('type', $type);

        $domains = array_merge($this->getVisibleDomains($user), $request->getDomains());
        if (count($domains) > 0) {
            $query->whereIn('domain_id', $domains);
        }

        $groups = array_merge($this->getVisibleGroups($user), $request->getUserGroups());
        if (count($groups) > 0) {
            $query->whereHas('groups', function ($q) use ($groups) {
                $q->whereIn('id', $groups);
            });
        }

        $users = $request->getUsers();
        if (count($users) > 0) {
            $query->whereIn('user_id', $users);
        }

        $products = $request->getProducts();
        if (count($products) > 0) {
            $query->whereIn('product_id', $products);
        }

        $from = $request->getFrom();
        $to = $request->getTo();
        if (!is_null($from) && !is_null($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        } else {
            if (!is_null($from)) {
                $query->whereDate('created_at', '>=', $from);
            }
            if (!is_null($to)) {
                $query->whereDate('created_at', '<=', $to);
            }
        }

        if (is_null($func) || is_null($window)) {
            $query->select(['value as _value', 'created_at as _time']);
        } else {
            $query->select([DB::raw("$func(value) as _value"), DB::raw("(FLOOR(UNIX_TIMESTAMP(created_at)/$window)*$window) * 1000 as _time")]);
            $query->groupBy('_time');
        }

        $query->orderBy('_time', 'desc');

        return $query;
    }

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @return array
     */
    public function getVisibleGroups(IProfileUser $user): array
    {
        if ($user->hasRole(config('larapress.profiles.form_role_profiles.super-role'))) {
            return [];
        } else {
            return $user->getAdministrateGroupIds();
        }
    }

    /**
     * Undocumented function
     *
     * @param IProfileUser $user
     * @return array
     */
    public function getVisibleDomains(IProfileUser $user): array
    {
        if ($user->hasRole(config('larapress.profiles.security.roles.affiliate'))) {
            return $user->getAffiliateDomainIds();
        }

        return [];
    }

    /**
     * Undocumented function
     *
     * @param int $domain_id
     * @param string $group
     * @param string $key
     *
     * @return int
     */
    public function removeMeasurement($domain_id, int $type, string|null $group, $key = null)
    {
        $query = MetricCounter::query()
            ->where('domain_id', $domain_id)
            ->where('type', $type);

        if (!is_null($group)) {
            $query->where('group', $group);
        }

        if (!is_null($key)) {
            $query->where('group', $key);
        }

        return $query->delete();
    }
}
