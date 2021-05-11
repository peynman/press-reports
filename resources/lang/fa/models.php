<?php

use Larapress\Reports\Flags\TaskReportStatus;

return [
    'task-reports' => [
        'status' => [
            TaskReportStatus::CREATED => 'ساخته شد',
            TaskReportStatus::EXECUTING => 'در حال اجرا',
            TaskReportStatus::FAILED => 'خطا',
            TaskReportStatus::SUCCESS => 'موفق',
        ],
    ],
];
