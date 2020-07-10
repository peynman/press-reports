<?php


namespace Larapress\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Larapress\Reports\Flags\TaskReportStatus;

/**
 * @property int            $id
 * @property string         $type
 * @property string         $title
 * @property string         $name
 * @property int            $status
 * @property \Carbon\Carbon $stopped_at
 * @property \Carbon\Carbon $stopped_at
 * @property string         $description
 * @property array          $data
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
    ];

    protected $casts = [
        'data' => 'array',
    ];

	protected $dates = [
		'stopped_at',
		'started_at',
    ];
}
