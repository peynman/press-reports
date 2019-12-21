<?php


namespace Larapress\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Larapress\CRUD\Base\ICRUDRenderService;
use Larapress\Dashboard\Dashboard\FileManagerViewProvider;
use Larapress\Dashboard\Dashboard\HomeCRUDViewProvider;

class FileManagerController extends Controller
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
        Route::get('file-manager-proxy', '\\'.self::class.'@proxyFileManager')
             ->name('control-panel.file-manager');

        Route::get('file-manager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')
             ->name('control-panel.file-manager.frame');
        Route::post('file-manager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload')
            ->name('control-panel.file-manager.upload');
    }


    public function proxyFileManager(Request $request)
    {
        $this->renderService->useViewProvider(new FileManagerViewProvider($this->renderService));
        return $this->renderService->getWidgets();
    }
}
