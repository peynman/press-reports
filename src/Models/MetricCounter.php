<?php

namespace Larapress\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Larapress\ECommerce\Models\Product;
use Larapress\Profiles\Models\Domain;
use Larapress\Profiles\Models\Group;
use Larapress\Profiles\IProfileUser;

/**
 * @property int            $id
 * @property string         $key
 * @property int            $type
 * @property string         $group
 * @property float          $value
 * @property string         $group
 * @property Domain         $domain
 * @property IProfileUser   $user
 * @property Product        $product
 * @property Group[]        $groups
 * @property int            $domain_id
 * @property int            $user_id
 * @property int            $group_id
 * @property \Carbon\Carbon $created_at
 */
class MetricCounter extends Model
{
    const UPDATED_AT = null;

    protected $table = 'metric_counters';

    public $timestamps = [
        'created_at',
    ];

    protected $fillable = [
        'key',
        'type',
        'group',
        'value',
        'domain_id',
        'user_id',
        'product_id',
        'created_at',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('larapress.crud.user.model'), 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'metric_counter_group_pivot',
            'metric_id',
            'group_id'
        );
    }
}
