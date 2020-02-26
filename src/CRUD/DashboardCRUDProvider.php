<?php

namespace Larapress\Dashboard\CRUD;

use Larapress\CRUD\Base\BaseCRUDProvider;
use Larapress\CRUD\Base\ICRUDProvider;

class DashboardCRUDProvider implements ICRUDProvider
{
    use BaseCRUDProvider;

    public $model = null;
}