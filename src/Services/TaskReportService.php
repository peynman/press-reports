<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;
use Larapress\Reports\Flags\TaskReportStatus;
use Larapress\Reports\Models\TaskReport;

class TaskReportService implements ITaskReportService {
    /**
     * Undocumented function
     *
     * @return void
     */
    /**
     * @param string   $type
     * @param string   $key
     * @param callable $callback
     *
     * @return mixed
     */
    public function makeSyncronizedTaskReport($type, $name, $callback) {
        /** @var TaskReport $task */
        $task = TaskReport::create([
            'type' => $type,
            'name' => $name,
            'status' => TaskReportStatus::CREATED,
        ]);
        $onSuccess = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::SUCCESS,
                'description' => $desc,
                'data' => $data,
                'stopped_at' => Carbon::now(),
            ]);
        };
        $onFailed = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::FAILED,
                'description' => $desc,
                'data' => $data,
                'stopped_at' => Carbon::now(),
            ]);
        };
        $onStarted = function ($desc, $data) use ($task) {
            $task->update([
                'status' => TaskReportStatus::EXECUTING,
                'description' => $desc,
                'data' => $data,
                'started_at' => Carbon::now(),
            ]);
        };
        return $callback($onStarted, $onSuccess, $onFailed);
    }
}
