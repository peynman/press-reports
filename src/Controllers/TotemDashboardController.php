<?php


namespace Larapress\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\Dashboard\Dashboard\HomeCRUDViewProvider;
use Larapress\Dashboard\Dashboard\TotemViewProvider;

class TotemDashboardController extends Controller
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
        Route::get('totem', '\\'.self::class.'@proxyTotem')
             ->name('control-panel.totem');
    }


    public function proxyTotem(Request $request)
    {
        $this->renderService->useViewProvider(new TotemViewProvider($this->renderService));
        return $this->renderService->getWidgets();
    }
}
