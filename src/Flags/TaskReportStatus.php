<?php


namespace Larapress\Reports\Flags;

use Larapress\Core\BaseType;

class TaskReportStatus
{
    use BaseType;

    const CREATED = 1;
    const EXECUTING = 2;
    const FAILED = 3;
    const SUCCESS = 4;

    const MAXVALUE = 4;
    const MINVALUE = 1;

    public static function getTitle($flag)
    {
        return self::_getTitle($flag, 'models.task-reports.status');
    }
}
