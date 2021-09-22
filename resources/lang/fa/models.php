<?php

use Larapress\Reports\Models\TaskReport;

return [
    'task-reports' => [
        'status' => [
            TaskReport::STATUS_CREATED => 'ساخته شد',
            TaskReport::STATUS_EXECUTING => 'در حال اجرا',
            TaskReport::STATUS_FAILED => 'خطا',
            TaskReport::STATUS_SUCCESS => 'موفق',
        ],
    ],
];
