<?php

namespace Larapress\Dashboard\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VueCRUDViewProvider extends BladeCRUDViewProvider
{

    /**
     * Show the list of the resource.
     *
     * @param Request $request
     * @return Response|string
     */
    public function getListViewName(Request $request)
    {
        return self::getThemeViewName('vue.app');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response|string
     */
    public function getCreateViewName(Request $request)
    {
        return self::getThemeViewName('vue.app');
    }

    /**
     * Handle incoming create

     * @param Request $request
     * @param $object
     *
     * @return Response|string
     */
    public function getPostCreateViewName(Request $request, $object)
    {
        return self::getThemeViewName('vue.app');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Response|string
     */
    function getUpdateViewName(Request $request)
    {
        return self::getThemeViewName('vue.app');
    }

    /**
     * Handle incoming edit
     *
     * @param         $object
     * @param Request $request
     *
     * @return Response|string
     */
    function getPostUpdateViewName(Request $request, $object)
    {
        return self::getThemeViewName('vue.app');
    }
    /**
     * @param Request $request
     * @return string
     */
    function getWidgetsViewName(Request $request)
    {
        return self::getThemeViewName('vue.app');
    }

    /**
     * @param Request $request
     * @return null|string
     */
    function getPostWidgetsViewName(Request $request)
    {
        return self::getThemeViewName('vue.app');
    }
}
