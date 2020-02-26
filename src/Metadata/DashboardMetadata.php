<?php

namespace Larapress\Dashboard\Metadata;

use Larapress\CRUD\Base\BasePermissionMetadata;
use Larapress\CRUD\Base\IPermissionsMetadata;
use Larapress\CRUD\Base\SingleSourceBaseMetadata;
use Larapress\CRUDRender\Base\BaseCRUDResourceView;
use Larapress\CRUDRender\Base\ICRUDViewRouting;

class DashboardMetadata extends SingleSourceBaseMetadata implements IPermissionsMetadata, ICRUDViewRouting
{
    use BasePermissionMetadata;
    use BaseCRUDResourceView;

    /***
     * get permissions required for each CRUD operation
     *
     * @return array
     */
    public function getPermissionVerbs()
    {
        return [
            self::VIEW,
        ];
    }

    /**
     * Permission group name.
     *
     * @return string
     */
    public function getPermissionObjectName()
    {
        return 'dashboard';
    }
}