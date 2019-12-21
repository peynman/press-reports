<?php


namespace Larapress\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\Dashboard\Dashboard\HomeCRUDViewProvider;
use Larapress\Dashboard\Dashboard\LaravelLogViewProvider;

class LaravelLogController extends Controller
{
    /**
     * @var ICRUDRenderService
     */
    private $renderService;

    public function __construct(ICRUDRenderService $crud)
    {
        $this->middleware('dashboard');
        $this->renderService = $crud;
        $this->renderService->useViewProvider(new HomeCRUDViewProvider($crud));
    }


    public static function registerRoutes()
    {
        Route::get('laravel-log', '\\'.self::class.'@proxyLaravelLog')
             ->name('control-panel.laravel-log');
    }


    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function proxyLaravelLog(Request $request)
    {
        $this->renderService->useViewProvider(new LaravelLogViewProvider($this->renderService));
        return $this->renderService->getWidgets();
    }
}
