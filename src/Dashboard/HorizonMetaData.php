<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\Dashboard\Base\SingleSourceBaseMetaData;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\CRUD\Base\IPermissionsMetaData;
use Larapress\Dashboard\Base\ITestableCRUD;
use Larapress\Dashboard\Base\PermissionMetaData;
use Larapress\Dashboard\Rendering\Menu\IMenuItemMetaData;

class HorizonMetaData extends SingleSourceBaseMetaData implements
    IPermissionsMetaData,
    IMenuItemMetaData,
    ITestableCRUD
{
    use PermissionMetaData;

    /***
     * get permissions required for each CRUD operation
     *
     * @return array
     */
    public function getPermissionsVerbs()
    {
        return ['horizon' => trans('permissions.verbs.control-panel.horizon')];
    }

    /**
     * Permission group name
     *
     * @return string
     */
    public function groupName()
    {
        return 'control-panel';
    }

    /**
     * Title to show for menu item in sidebar
     *
     * @return string
     */
    public function title()
    {
        return 'Horizon';
    }

    /**
     * The key that is used for finding active links
     *
     * @return string
     */
    public function key()
    {
        return 'horizon';
    }

    /**
     * Icon for this menu item
     *
     * @return string
     */
    public function icon()
    {
        return '';
    }

    /**
     * url to show when clicked
     *
     * @return string
     */
    public function url()
    {
        return route('control-panel.horizon', ['']);
    }

    /**
     * Required permissions to see this menu item
     *
     * @return array
     */
    public function viewPermissions()
    {
        return [$this->verbName('horizon')];
    }

    /**
     * Required roles to see this menu item
     *
     * @return array
     */
    public function viewRoles()
    {
        return [];
    }


    /**
     * @return ICRUDProvider
     */
    function getCRUDProvider()
    {
        return null;
    }

    function plural()
    {
        return trans('permissions.verbs.control-panel.horizon');
    }
}
