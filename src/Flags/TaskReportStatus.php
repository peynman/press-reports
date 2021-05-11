<?php


namespace Larapress\Reports\Flags;

use Larapress\CRUD\BaseType;

class TaskReportStatus
{
    use BaseType;

    const CREATED = 1;
    const EXECUTING = 2;
    const FAILED = 3;
    const SUCCESS = 4;

    const MINVALUE = 1;
    const MAXVALUE = 4;

    public static function getTitle($flag)
    {
        return self::__getFlagProperty($flag, 'larapress::models.task-reports.status');
    }
}
