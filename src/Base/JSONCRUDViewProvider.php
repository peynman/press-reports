<?php

namespace Larapress\Dashboard\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Larapress\CRUDRender\Base\ICRUDViewDataProvider;

class JSONCRUDViewProvider implements ICRUDViewDataProvider
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
     * @param null $extend
     * @return array
     */
    protected function getCommonViewParams($extend = null)
    {
        return array_merge([
            'sidebar' => [
                'items' => config('larapress.dashboard.sidebar.items'),
            ],
            'metadata' => $this->getMetaData(),
        ], !is_null($extend) ? $extend:[]);
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
        return $this->getCommonViewParams();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCreateViewData(Request $request)
    {
        return $this->getCommonViewParams();
    }

    /**
     * @param Request $request
     * @param $object
     *
     * @return array
     */
    public function getUpdateViewData(Request $request, $object)
    {
        return $this->getCommonViewParams();
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
        return $this->getCommonViewParams();
    }

    /**
     * @param $object
     * @param Request $request
     *
     * @return array
     */
    public function getPostUpdateViewData(Request $request, $object)
    {
        return $this->getCommonViewParams();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getWidgetsViewData(Request $request)
    {
        return $this->getCommonViewParams();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPostWidgetsViewData(Request $request)
    {
        return $this->getCommonViewParams();
    }


    /**
     * @return \Larapress\CRUDRender\Table\ITableViewMetaData|\Larapress\CRUDRender\Form\ICRUDFormMetaData
     */
    public function getMetaData()
    {
        return $this->metadata;
    }
}
