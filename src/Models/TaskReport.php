<?php


namespace Larapress\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Larapress\Profiles\IProfileUser;

/**
 * @property int            $id
 * @property int            $author_id
 * @property IProfileUser   $author
 * @property string         $type
 * @property string         $title
 * @property string         $name
 * @property int            $status
 * @property string         $description
 * @property array          $data
 * @property \Carbon\Carbon $stopped_at
 * @property \Carbon\Carbon $stopped_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TaskReport extends Model
{
    protected $table = 'task_reports';

    protected $fillable = [
        'type',
        'name',
        'status',
        'description',
        'data',
        'stopped_at',
        'started_at',
        'author_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $dates = [
        'stopped_at',
        'started_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(config('larapress.crud.user.model'), 'author_id');
    }
}
