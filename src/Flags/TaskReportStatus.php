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
}
