<?php

namespace Larapress\Reports\Services;

interface IReportsServiceProvider
{
    public function getFiltersForReports($user, $options);
}
