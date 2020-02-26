<?php

namespace Larapress\Dashboard\Rendering;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Larapress\CRUDRender\Base\ICRUDBladeViewProvider;
use Larapress\CRUDRender\Base\ICRUDViewDataProvider;
use Larapress\CRUDRender\Menu\IMenuRenderService;

class BladeCRUDViewProvider implements ICRUDBladeViewProvider, ICRUDViewDataProvider
{
    private $metadata;

    /**
     * BladeCRUDViewProvider constructor.
     *
     * @param $metadata
     */
    public function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Show the list of the resource.
     *
     * @param Request $request
     * @return Response|string
     */
    public function getListViewName(Request $request)
    {
        return self::getThemeViewName('blade.pages.browse');
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getListViewData(Request $request)
    {
        return $this->getCommonViewParams();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response|string
     */
    public function getCreateViewName(Request $request)
    {
        return self::getThemeViewName('blade.pages.create');
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCreateViewData(Request $request)
    {
        return $this->getCommonViewParams();
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
        return self::getThemeViewName('blade.pages.create');
    }

    /**
     * @param  Request $request
     * @param  $object
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPostCreateViewData(Request $request, $object)
    {
        $extra = [];
        if (!($object instanceof \Exception)) {
            $extra['target'] = $object;
            $extra[$this->getViewSuccessMessageKey($request)] = trans('forms.messages.create_success', [
                'target' => $this->getMetadata()->title()
            ]);
        }
        return $this->getCommonViewParams($extra);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Response|string
     */
    public function getUpdateViewName(Request $request)
    {
        return self::getThemeViewName('blade.pages.edit');
    }

    /**
     * @param Request $request
     * @param $object
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getUpdateViewData(Request $request, $object)
    {
        return $this->getCommonViewParams([
            'target' => $object,
            'objectTitle' => trans('forms.edit_title', [
                'target' => $this->getMetadata()->title()
            ])
        ]);
    }

    /**
     * Handle incoming edit
     *
     * @param         $object
     * @param Request $request
     *
     * @return Response|string
     */
    public function getPostUpdateViewName(Request $request, $object)
    {
        return self::getThemeViewName('blade.pages.edit');
    }

    /**
     * @param Request $request
     *
     * @param $object
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPostUpdateViewData(Request $request, $object)
    {
        $extras = [
            'objectTitle' => trans('forms.edit_title', [
                'target' => $this->getMetadata()->title()
            ])
        ];
        if (! $object instanceof \Exception) {
            $extras['target'] = $object;
            $extras[$this->getViewSuccessMessageKey($request)] = trans('forms.messages.edit_success', [
                'target' => $this->getMetadata()->title()
            ]);
        }

        return $this->getCommonViewParams($extras);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getViewValidationErrorKey(Request $request)
    {
        return 'validation';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getViewErrorMessageKey(Request $request)
    {
        return 'error';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getViewSuccessMessageKey(Request $request)
    {
        return 'success';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getReturningDataKey(Request $request)
    {
        return 'target';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getValidationErrorMessage(Request $request)
    {
        return trans('forms.messages.validation_error');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getWidgetsViewName(Request $request)
    {
        return self::getThemeViewName('blade.pages.widgets');
    }

    /**
     * @param Request $request
     * @return null|string
     */
    public function getPostWidgetsViewName(Request $request)
    {
        return self::getThemeViewName('blade.pages.widgets');
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getWidgetsViewData(Request $request)
    {
        return $this->getCommonViewParams([
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPostWidgetsViewData(Request $request)
    {
        return $this->getCommonViewParams([

        ]);
    }

    public static function getThemeViewName($viewName)
    {
        $theme = config('larapress.dashboard.theme.blade.name');
        $package = config('larapress.dashboard.theme.blade.namespace');
        $view = null;
        if (isset($theme) && !Str::startsWith($viewName, 'themes.'.$theme)) {
            $view = (isset($package) ? $package.'::':'').'themes.'.$theme.'.'.$viewName;
        } else {
            $view = (isset($package) ? $package.'::':'').$viewName;
        }
        return $view;
    }

    /**
     * @return \Larapress\CRUDRender\Table\ICRUDTableMetadata|\Larapress\CRUDRender\Form\ICRUDFormMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param null $extend
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getCommonViewParams($extend = null)
    {
        /** @var IMenuRenderService $renderService */
        $renderService = app()->make(IMenuRenderService::class);
        $renderService->useItemRenderService(new BladeMenuItemRenderer());

        return array_merge([
            'menuRenderService' => $renderService,
            'sidebar' => [
                'items' => config('larapress.blade.sidebar.items'),
            ],
            'metadata' => $this->getMetadata(),
        ], !is_null($extend) ? $extend:[]);
    }
}
