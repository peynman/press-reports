<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\Dashboard\Base\SingleSourceBaseMetaData;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\CRUD\Base\IPermissionsMetaData;
use Larapress\Dashboard\Base\ITestableCRUD;
use Larapress\Dashboard\Base\PermissionMetaData;
use Larapress\Dashboard\Rendering\Menu\IMenuItemMetaData;

class TotemMetaData extends SingleSourceBaseMetaData implements
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
        return ['totem' => trans('permissions.verbs.control-panel.totem')];
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
        return 'Totem';
    }

    /**
     * The key that is used for finding active links
     *
     * @return string
     */
    public function key()
    {
        return 'totem';
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
        return route('control-panel.totem', ['']);
    }

    /**
     * Required permissions to see this menu item
     *
     * @return array
     */
    public function viewPermissions()
    {
        return [$this->verbName('totem')];
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
        return trans('permissions.verbs.control-panel.totem');
    }
}
