<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\CRUD\Base\ICRUDViewProvider;
use Larapress\Dashboard\Rendering\Menu\IMenuItemMetaData;
use Larapress\Dashboard\Rendering\Table\ITableViewMetaData;

class HomeCRUDViewProvider implements ICRUDViewProvider
{
    use BladeCRUDViewProvider;

    /**
     * @var ICRUDRenderService
     */
    private $service;

    public function __construct(ICRUDRenderService $service)
    {
        $this->service = $service;
    }


    /**
     * @return ICRUDRenderService
     */
    public function getRenderService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getAccordionName()
    {
        return 'dashboard';
    }

    /**
     * @return  IMenuItemMetaData|ITableViewMetaData
     */
    public function getMetaData()
    {
        return DashboardMetaData::instance();
    }
}
