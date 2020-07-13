<?php

namespace Larapress\Reports\Services;

use Carbon\Carbon;
use Larapress\Reports\Flags\TaskReportStatus;
use Larapress\Reports\Models\TaskReport;

class TaskReportService implements ITaskReportService {

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
    public function startSyncronizedTaskReport(string $type, string $name, string $desc, array $data, $callback) {
        /** @var TaskReport $task */
        $task = TaskReport::firstOrCreate([
            'type' => $type,
            'name' => $name,
            'status' => TaskReportStatus::CREATED,
        ]);
        $task->update([
            'status' => TaskReportStatus::EXECUTING,
            'description' => $desc,
            'data' => $data,
            'started_at' => Carbon::now(),
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
        return $callback($onSuccess, $onFailed);
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
    public function scheduleTask(string $type, string $name, string $desc, array $data, $autoStart = false) {
        /** @var TaskReport $task */
        return TaskReport::firstOrCreate([
            'type' => $type,
            'name' => $name,
            'status' => TaskReportStatus::CREATED,
        ], [
            'data' => array_merge($data, ['auto_start' => $autoStart]),
            'desc' => $desc,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $type
     * @param string $name
     * @return void
     */
    public function executeScheduledTask(string $type, string $name) {

    }
}
