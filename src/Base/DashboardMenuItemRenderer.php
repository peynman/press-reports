<?php


namespace Larapress\Dashboard\Base;

use Larapress\CRUDRender\Menu\AccordionMenuItemMetaData;
use Larapress\CRUDRender\Menu\HeaderMenuMetaData;
use Larapress\CRUDRender\Menu\IMenuItemMetaData;
use Larapress\CRUDRender\Menu\IMenuItemRenderService;
use Larapress\CRUDRender\Menu\IMenuRenderService;

class DashboardMenuItemRenderer implements IMenuItemRenderService
{
    /**
     * @param IMenuRenderService        $render_service
     * @param AccordionMenuItemMetaData $menu
     * @param array                     $activeLinks
     *
     * @param bool                      $isSubMenu
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function renderAccordion($render_service, $menu, $activeLinks, $isSubMenu = false)
    {
        $active = false;
        foreach ($menu->getItems() as $item) {
            if (in_array($item->getMenuKey(), $activeLinks)) {
                $active = true;
            }
        }

        return view(BladeCRUDViewProvider::getThemeViewName('dashboard.menus.sidebar.item-accordion'), [
            'item' => $menu,
            'active_class' => $active ? 'active':'',
            'actives' => $activeLinks,
            'renderer' => $render_service,
            'is_sub_menu' => $isSubMenu,
        ]);
    }

    /**
     * @param IMenuRenderService $render_service
     * @param IMenuItemMetaData  $menu
     * @param array              $activeLinks
     * @param bool               $isSubMenu
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function renderSingle($render_service, $menu, $activeLinks, $isSubMenu = false)
    {
        return view(BladeCRUDViewProvider::getThemeViewName('dashboard.sidebar.item-link'), [
            'item' => $menu,
            'active_class' => in_array($menu->getMenuKey(), $activeLinks) ? 'active' : '',
            'renderer' => $render_service,
            'is_sub_menu' => $isSubMenu,
        ]);
    }

    /**
     * @param IMenuRenderService        $render_service
     * @param HeaderMenuMetaData $menu
     * @param array              $activeLinks
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function renderHeader($render_service, $menu, $activeLinks)
    {
        return view(BladeCRUDViewProvider::getThemeViewName('dashboard.sidebar.item-group'), [
            'item' => $menu,
            'renderer' => $render_service,
        ]);
    }
}
