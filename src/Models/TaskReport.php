<?php


namespace Larapress\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Larapress\Dashboard\Flags\TaskReportStatus;

/**
 * @property int            $id
 * @property string         $type
 * @property string         $title
 * @property string         $key
 * @property int            $status
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
        'key',
        'status',
        'description',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];


    /**
     * @param string   $type
     * @param string   $key
     * @param callable $callback
     *
     * @return mixed
     */
    public static function makeReport($type, $key, $callback)
    {
        /** @var TaskReport $task */
        $task = TaskReport::create([
            'type' => $type,
            'key' => $key,
            'status' => TaskReportStatus::CREATED,
        ]);
        $onSuccess = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::SUCCESS,
                'description' => $desc,
                'data' => $data
            ]);
        };
        $onFailed = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::FAILED,
                'description' => $desc,
                'data' => $data
            ]);
        };
        $onStarted = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::EXECUTING,
                'description' => $desc,
                'data' => $data
            ]);
        };
        return $callback($onStarted, $onSuccess, $onFailed);
    }
}
