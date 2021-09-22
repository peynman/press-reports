<?php

namespace Larapress\Reports\Services\TaskScheduler;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Events\CRUDVerbEvent;
use Larapress\CRUD\Exceptions\AppException;
use Larapress\Reports\CRUD\TaskReportsCRUDProvider;
use Larapress\Reports\Models\TaskReport;

class TaskSchedulerService implements ITaskSchedulerService
{

    /**
     * Undocumented function
     *
     * @param string $type
     * @param string $name
     * @param string $desc
     * @param array $data
     * @param [type] $callback
     * @return void
     */
    public function startSyncronizedTaskReport(string $type, string $name, string $desc, array $data, $callback)
    {
        /** @var TaskReport $task */
        $task = TaskReport::firstOrCreate([
            'type' => $type,
            'name' => $name,
            'status' => TaskReport::STATUS_CREATED,
        ]);
        $task->update([
            'status' => TaskReport::STATUS_EXECUTING,
            'description' => $desc,
            'data' => $data,
            'started_at' => Carbon::now(),
        ]);
        CRUDVerbEvent::dispatch(Auth::user(), $task, TaskReportsCRUDProvider::class, Carbon::now(), 'queue');
        $onSuccess = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReport::STATUS_SUCCESS,
                'description' => $desc,
                'data' => $data,
                'stopped_at' => Carbon::now(),
            ]);
            CRUDVerbEvent::dispatch(Auth::user(), $task, TaskReportsCRUDProvider::class, Carbon::now(), 'queue');
        };
        $onFailed = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReport::STATUS_FAILED,
                'description' => $desc,
                'data' => $data,
                'stopped_at' => Carbon::now(),
            ]);
            CRUDVerbEvent::dispatch(Auth::user(), $task, TaskReportsCRUDProvider::class, Carbon::now(), 'queue');
        };
        $onUpdate = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReport::STATUS_EXECUTING,
                'description' => $desc,
                'data' => $data,
                'stopped_at' => Carbon::now(),
            ]);
            CRUDVerbEvent::dispatch(Auth::user(), $task, TaskReportsCRUDProvider::class, Carbon::now(), 'queue');
        };
        return $callback($onUpdate, $onSuccess, $onFailed);
    }


    /**
     * Undocumented function
     *
     * @param string $type
     * @param string $name
     * @param string $desc
     * @param array $data
     * @return TaskReport
     */
    public function scheduleTask(string $type, string $name, string $desc, array $data, $autoStart = false)
    {
        /** @var TaskReport $task */
        return TaskReport::firstOrCreate([
            'type' => $type,
            'name' => $name,
            'status' => TaskReport::STATUS_CREATED,
        ], [
            'data' => array_merge($data, ['auto_start' => $autoStart]),
            'desc' => $desc,
        ]);
    }

    /**
     * Undocumented function
     * @return TaskReport[]
     */
    public function queueScheduledTasks()
    {
        /** @var TaskReport[] */
        $tasks = TaskReport::where('status', TaskReport::STATUS_CREATED)->get();
        foreach ($tasks as $task) {
            $typeClass = $task->type;
            /** @var ITaskHandler */
            $handler = new $typeClass();
            if (isset($task->data['auto_start']) && $task->data['auto_start']) {
                if (!isset($task->data['queued_at'])) {
                    if (is_string($task->data['auto_start'])) {
                        $timestamp = Carbon::parse($task->data['auto_start']);
                    } else {
                        $handler->handle($task);
                        $data = $task->data;
                        $data['queued_at'] = Carbon::now();
                        $task->update([
                            'data' => $data,
                        ]);
                    }
                }
            }
        }

        return $tasks;
    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @return TaskReport
     */
    public function queueScheduledTask($id)
    {
        /** @var TaskReport */
        $task = TaskReport::find($id);
        if ($task->status === TaskReport::STATUS_EXECUTING) {
            throw new AppException(AppException::ERR_ALREADY_EXECUTED);
        }

        $typeClass = $task->type;
        /** @var ITaskHandler */
        $handler = new $typeClass();

        $handler->handle($task);
        $data = $task->data;
        $data['queued_at'] = Carbon::now();
        $task->update([
            'data' => $data,
        ]);

        return $task;
    }
}
