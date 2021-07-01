<?php

namespace Larapress\Reports\Services\Reports;

interface IReportsServiceProvider
{
    public function getFiltersForReports($user, $options);
}
