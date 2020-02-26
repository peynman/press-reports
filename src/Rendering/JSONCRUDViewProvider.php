<?php

namespace Larapress\Dashboard\Rendering;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Larapress\CRUD\Base\ICRUDFilterStorage;
use Larapress\CRUD\Translation\Lang\Persian;
use Larapress\CRUD\Translation\Lang\Roman;
use Larapress\CRUD\Translation\TranslationHelper;
use Larapress\CRUDRender\Base\BaseJSONRenderProvider;
use Larapress\CRUDRender\Base\ICRUDViewDataProvider;
use Larapress\CRUDRender\Menu\AccordionMenuItemMetadata;
use Larapress\CRUDRender\Menu\HeaderMenuMetadata;
use Larapress\Dashboard\CRUD\DashboardCRUDProvider;

class JSONCRUDViewProvider implements ICRUDViewDataProvider
{
    /** @var \Larapress\CRUDRender\Table\ICRUDTableMetadata|\Larapress\CRUDRender\Form\ICRUDFormMetadata|\Larapress\CRUD\Base\IPermissionsMetadata */
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
     * @param $menuItems
     * @return array
     */
    protected function getMenuItems($menuItems)
    {
        $items = [];

        foreach ($menuItems as $item) {
            $metadata = null;
            $component = null;
            if (is_string($item) || method_exists($item, 'instance')) {
                $metadata = call_user_func([$item, 'instance']);
                $component = 'lpd-menu-item-single';
                if (method_exists($metadata, 'getMenuItemMetadata')) {
                    $menu = call_user_func([$metadata, 'getMenuItemMetadata']);
                    array_push($items, [
                        'component' => $component,
                        'props' => $menu,
                    ]);
                }
            } else if ($item instanceof HeaderMenuMetadata) {
                $metadata = $item;
                $component = 'lpd-menu-item-header';

                if (method_exists($metadata, 'getMenuItemMetadata')) {
                    $menu = call_user_func([$metadata, 'getMenuItemMetadata']);
                    array_push($items, [
                        'component' => $component,
                        'props' => $menu,
                    ]);
                }
            } else if ($item instanceof AccordionMenuItemMetadata) {
                $metadata = $item;
                $component = 'lpd-menu-item-accordion';

                if (method_exists($metadata, 'getMenuItemMetadata')) {
                    $menu = call_user_func([$metadata, 'getMenuItemMetadata']);
                    $menu['items'] = $this->getMenuItems(array_values($menu['items']));
                    array_push($items, [
                        'component' => $component,
                        'props' => $menu,
                    ]);
                }
            }
        }

        return $items;
    }

    protected function getLanguageParams() {
        $lang = TranslationHelper::getLocaleLanguage(app()->getLocale());

        return [
            'rtl' => $lang->isRTL(),
            'name' => $lang->getName(),
            'title' => $lang->getTitle(),
            'available' => [
                [
                    'id' => 'en',
                    'title' => 'English',
                    'provider' => new Roman(),
                    'abbr' => 'En',
                ],
                [
                    'id' => 'fa',
                    'title' => 'Farsi',
                    'provider' => new Persian(),
                    'abbr' => 'Fa',
                ],
                [
                    'id' => 'ar',
                    'title' => 'Arabic',
                    'provider' => new Persian(),
                    'abbr' => 'Ar',
                ],
            ],
            'translations' => [
                'dashboard' => trans(config('larapress.dashboard.theme.translations.namespace').'::dashboard'),
                'forms' => trans(config('larapress.dashboard.theme.translations.namespace').'::forms'),
                'tables' => trans(config('larapress.dashboard.theme.translations.namespace').'::tables'),
                'validations' => trans('validation'),
            ]
        ];
    }

    /**
     * @param array $extend
     * @return array
     */
    protected function getCommonParams($extend = [])
    {
        /** @var \Larapress\CRUD\ICRUDUser $user */
        $user = Auth::user();
        $user['permissions'] = $user->getPermissions();

        /** @var ICRUDFilterStorage $storageService */
        $storageService = app(ICRUDFilterStorage::class);

        return [
            'config' => array_merge_recursive([
                'page' => [
                    'title' => trans(config('larapress.dashboard.theme.translations.namespace').'::dashboard.pages.title'),
                    'user' => $user->toArray(),
                ],
                'language' => $this->getLanguageParams(),
                'content' => [
                    'metadata' => BaseJSONRenderProvider::getMetadataToArray($this->metadata),
                ],
                'sidebar' => [
                    'title' => 'Sidebar',
                    'items' => $this->getMenuItems(config('larapress.dashboard.sidebar')),
                ],
                'options' => [
                    'title' => trans(config('larapress.dashboard.theme.translations.namespace').'::dashboard.options.title'),
                    'update' => [
                        'url' => route('dashboard.query.filter'),
                        'method' => 'POST',
                    ],
                    'filters' => [
                        'options' => $storageService->getFilters(
                            $storageService->getFilterKey('options', DashboardCRUDProvider::class),
                            null,
                            $user->id
                        ),
                        'theme' => $storageService->getFilters(
                            $storageService->getFilterKey('theme', DashboardCRUDProvider::class),
                            null,
                            $user->id
                        ),
                    ],
                ],
                'notifications' => [
                    'title' => trans(config('larapress.dashboard.theme.translations.namespace').'::dashboard.notifications.title'),
                ],
                'messages' => [
                    'title' => trans(config('larapress.dashboard.theme.translations.namespace').'::dashboard.messages.title'),
                ],
            ], $extend)
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    function getViewValidationErrorKey(Request $request)
    {
        return 'validation';
    }

    /**
     * @param Request $request
     * @return string
     */
    function getViewErrorMessageKey(Request $request)
    {
        return 'error';
    }

    /**
     * @param Request $request
     * @return string
     */
    function getViewSuccessMessageKey(Request $request)
    {
        return 'success';
    }

    /**
     * @param Request $request
     * @return string
     */
    function getReturningDataKey(Request $request)
    {
        return 'target';
    }

    /**
     * @param Request $request
     * @return string
     */
    function getValidationErrorMessage(Request $request)
    {
        return trans('forms.messages.validation_error');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getListViewData(Request $request)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-browse',
                'props' => [
                    'prevRequest' => $request->all(),
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCreateViewData(Request $request)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-create',
                'props' => [
                    'prevRequest' => $request->all(),
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param $object
     *
     * @return array
     */
    public function getUpdateViewData(Request $request, $object)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-update',
                'props' => [
                    'prevRequest' => $request->all(),
                    'initValue' => $object,
                    'metadata' => BaseJSONRenderProvider::getMetadataToArray($this->metadata, $object),
                ],
            ],
        ]);
    }

    /**
     * @param $object
     *
     * @param Request $request
     *
     * @return array
     */
    public function getPostCreateViewData(Request $request, $object)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-create',
                'props' => [
                    'prevRequest' => $request->all(),
                    'initValue' => $object,
                    'metadata' => BaseJSONRenderProvider::getMetadataToArray($this->metadata, $object),
                ],
            ],
        ]);
    }

    /**
     * @param $object
     * @param Request $request
     *
     * @return array
     */
    public function getPostUpdateViewData(Request $request, $object)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-update',
                'props' => [
                    'prevRequest' => $request->all(),
                    'initValue' => $object,
                    'metadata' => BaseJSONRenderProvider::getMetadataToArray($this->metadata, $object),
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getWidgetsViewData(Request $request)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-widgets',
                'props' => [
                    'prevRequest' => $request->all(),
                ],
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPostWidgetsViewData(Request $request)
    {
        return $this->getCommonParams([
            'content' => [
                'component' => 'lpd-crud-widgets',
                'props' => [
                    'prevRequest' => $request->all(),
                ],
            ],
        ]);
    }


    /**
     * @return \Larapress\CRUDRender\Table\ICRUDTableMetadata|\Larapress\CRUDRender\Form\ICRUDFormMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
