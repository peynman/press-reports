<?php


namespace Larapress\Dashboard\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Larapress\CRUD\Models\Settings;
use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\CRUD\Base\ICRUDService;
use Larapress\Dashboard\Dashboard\DashboardMetaData;

/**
 * Trait DashboardCRUDViewController
 *
 * @property ICRUDService $crudService
 * @property ICRUDRenderService $renderService
 *
 * @package Larapress\Dashboard\Controllers
 */
trait DashboardCRUDViewController
{
    public function list()
    {
        return $this->renderService->getListView();
    }
    public function filter(Request $request)
    {
        return $this->renderService->postFilterView($request);
    }
    public function postExport(Request $request)
    {
        return $this->crudService->export($request);
    }
    public function create()
    {
        return $this->renderService->getCreateView();
    }
    public function createPost(Request $request)
    {
        return $this->renderService->postCreateView($request);
    }
    public function edit($id)
    {
        return $this->renderService->getEditView($id);
    }
    public function editPost(Request $request, $id)
    {
        return $this->renderService->postEditView($request, $id);
    }
    public function reports()
    {
        return $this->renderService->getWidgets();
    }

    public function reportsPost(Request $request)
    {
        $metadata = $this->renderService->getViewProvider()->getMetaData();
        $action = $request->get('action');
        $user = Auth::user();
        switch ($action) {
            case 'toggle-dashboard':
                $name = $request->get('name');
                if ($metadata instanceof DashboardMetaData && Str::startsWith($name, "dashboard_")) {
                    $name = substr($name, strlen("dashboard_"));
                }
                $val = Settings::getSettings('metrics.'.$name.'.on_dashboard', false, $user->id);
                if ($val === false) {
                    Settings::putSettings(
                        'metrics.'.$name.'.on_dashboard',
                        [
                            'metadata' => get_class($metadata),
                            'name' => $name,
                        ],
                        $user->id
                    );
                } else {
                    Settings::putSettings('metrics.'.$name.'.on_dashboard', false, $user->id);
                }
                break;
            case 'switch-range':
                Settings::putSettings('metrics.'.$request->get('name').'.range', $request->get('range'), $user->id);
                break;
        }

        return $this->reports();
    }

    protected static function viewRoutes($name, $controller)
    {
        if (is_string($controller)) {
            if (!Str::startsWith($controller, 'App')) {
                $controller = 'Dashboard\\CRUD\\'.$controller;
            } else {
                $controller = '\\'.$controller;
            }
        }

        Route::get($name, $controller.'@list')->name($name.'.view');
        Route::get($name.'/reports', $controller.'@reports')->name($name.'.view.reports');
        Route::post($name.'/export', $controller.'@postExport')->name($name.'.view.export.post');
        Route::post($name.'/reports', $controller.'@reportsPost')->name($name.'.view.reports.post');
        Route::post($name, $controller.'@filter')->name($name.'.view.post');
        Route::get($name.'/create', $controller.'@create')->name($name.'.create');
        Route::post($name.'/create', $controller.'@createPost')->name($name.'.create.post');
        Route::get($name.'/edit/{id}', $controller.'@edit')->name($name.'.edit');
        Route::post($name.'/edit/{id}', $controller.'@editPost')->name($name.'.edit.post');
    }
}
