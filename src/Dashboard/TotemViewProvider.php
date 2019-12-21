<?php


namespace Larapress\Dashboard\Dashboard;

use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\CRUD\Base\ICRUDViewProvider;

class TotemViewProvider implements ICRUDViewProvider
{
    use BladeCRUDViewProvider;

    /**
     * @var ICRUDRenderService
     */
    private $render_service;

    /**
     * BookCRUDViewProvider constructor.
     *
     * @param ICRUDRenderService $render_service
     */
    public function __construct(ICRUDRenderService $render_service)
    {
        $this->render_service = $render_service;
    }

    /**
     * @return ICRUDRenderService
     */
    public function getRenderService()
    {
        return $this->render_service;
    }

    /**
     * @return string
     */
    public function getAccordionName()
    {
        return 'control-panel';
    }

    public function getMetaData()
    {
        return TotemMetaData::instance();
    }

    function getWidgetsViewName()
    {
        return BladeCRUDViewProvider::getThemeViewName('dashboard.pages.totem');
    }


    function getPostWidgetsViewName($request)
    {
        return BladeCRUDViewProvider::getThemeViewName('dashboard.pages.totem');
    }

    /**
     * @return array
     */
    function getWidgetsViewData()
    {
        return $this->getCommonViewParams([
        ]);
    }
}
