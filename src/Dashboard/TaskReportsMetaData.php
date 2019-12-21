<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\Dashboard\Base\SingleSourceBaseMetaData;
use Larapress\CRUD\Base\ICRUDProvider;
use Larapress\CRUD\Base\IPermissionsMetaData;
use Larapress\Dashboard\Base\ITestableCRUD;
use Larapress\Dashboard\Base\PermissionMetaData;
use Larapress\Dashboard\CRUD\TaskReportsProvider;
use Larapress\Dashboard\Rendering\Form\BaseCRUDFormMetaData;
use Larapress\Dashboard\Rendering\Form\ICRUDFormMetaData;
use Larapress\Dashboard\Rendering\Menu\IMenuItemMetaData;
use Larapress\Dashboard\Rendering\Table\ITableViewMetaData;
use Larapress\Dashboard\Rendering\Table\TableViewColumn;
use Larapress\Dashboard\Rendering\Table\TableViewMetaData;

class TaskReportsMetaData extends SingleSourceBaseMetaData implements
    IPermissionsMetaData,
    IMenuItemMetaData,
    ITableViewMetaData,
    ITestableCRUD,
    ICRUDFormMetaData
{
    use PermissionMetaData;
    use BaseCRUDFormMetaData;
    use TableViewMetaData;

    /***
     * get permissions required for each CRUD operation
     *
     * @return array
     */
    public function getPermissionsVerbs()
    {
        return [
            self::VIEW => trans('models.task-reports.permissions.view'),
        ];
    }

    /**
     * Permission group name
     *
     * @return string
     */
    public function groupName()
    {
        return 'task-reports';
    }

    /**
     * Title to show for menu item in sidebar
     *
     * @return string
     */
    public function title()
    {
        return trans('models.task-reports.sidebar');
    }

    /**
     * The key that is used for finding active links
     *
     * @return string
     */
    public function key()
    {
        return 'task-reports';
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
        return $this->viewUrl();
    }

    /**
     * Required permissions to see this menu item
     *
     * @return array
     */
    public function viewPermissions()
    {
        return [$this->view()];
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

    public function queryParams()
    {
        return [];
    }

    public function getTableColumns()
    {
        return [
            TableViewColumn::id(),
            TableViewColumn::column(trans('tables.column.type'), 'type'),
            TableViewColumn::column(trans('tables.column.key'), 'key'),
            TableViewColumn::column(trans('tables.column.description'), 'description'),
            TableViewColumn::filter(trans('tables.column.status'), 'status', 'JSBridge.getTaskStatus(data)', true),
            TableViewColumn::datetime(),
            TableViewColumn::datetime(trans('tables.column.updated_at')),
        ];
    }

    public function hasCreate()
    {
        return false;
    }
    public function hasEdit()
    {
        return false;
    }
    public function hasDelete()
    {
        return false;
    }

    /**
     * @return ICRUDProvider
     */
    function getCRUDProvider()
    {
        return new TaskReportsProvider();
    }

    public function getViewControllerRouteName()
    {
        return 'task-reports';
    }

    /**
     * @return string
     */
    public function singular()
    {
        return trans('models.task-reports.name.singular');
    }

    function plural()
    {
        return trans('models.task-reports.name.plural');
    }

    public function getCreateFields()
    {
        return [];
    }

    public function getUpdateFields($object = null)
    {
        return [];
    }

    public function getControllerRouteName()
    {
        return 'task-reports';
    }
}
