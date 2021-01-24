<?php

namespace Larapress\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Larapress\Profiles\Models\Domain;
use Larapress\Reports\Services\MetricCounterGroupCartRelationship;

/**
 * @property int            $id
 * @property string         $key
 * @property float          $value
 * @property string         $group
 * @property Domain         $domain
 * @property \Carbon\Carbon $created_at
 */
class MetricCounter extends Model
{
    const UPDATED_AT = null;

    protected $table = 'metrics_counters';

    public $timestamps = [
        'created_at',
    ];

    protected $fillable = [
        'key',
        'group',
        'value',
        'domain_id',
        'created_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function group_cart() {
        return new MetricCounterGroupCartRelationship(
            $this
        );
    }
}
