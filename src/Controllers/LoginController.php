<?php

namespace Larapress\Dashboard\Controllers;

use Larapress\CRUD\Controllers\LoginController as AuthLoginController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Larapress\Core\Exceptions\AppException;
use Larapress\Core\Exceptions\ValidationException;
use Larapress\CRUD\Requests\LoginRequest;
use Larapress\Dashboard\Dashboard\BladeCRUDViewProvider;

class LoginController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout']);
        $this->middleware('dashboard')->only(['logout']);
    }

    public function viewLoginForm()
    {
        return view(BladeCRUDViewProvider::getThemeViewName('dashboard.auth.login'));
    }

    public function login(LoginRequest $request)
    {
        try {
            AuthLoginController::authenticateWithRequest($request, 'web');
            return redirect()->route(config('larapress.dashboard.redirects.login'));
        } catch (ValidationException $exception) {
            return redirect()->route('dashboard.login.view')->with([
                'validation' => $exception->getValidations()->errors()
            ]);
        } catch (AppException $exception) {
            if ($exception->getErrorCode() === AppException::ERR_INVALID_CREDENTIALS) {
                return redirect()->route('dashboard.login.view')->with([
                    'error' => 'Wrong username and/or password.'
                ]);
            }
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route(config('larapress.dashboard.redirects.logout'));
    }

    public static function registerRoutes()
    {
        Route::get('/admin/login', '\\'.self::class.'@viewLoginForm')
             ->name('dashboard.login.view');
        Route::post('/admin/login', '\\'.self::class.'@login')
             ->name('dashboard.login.post');
        Route::get('/admin/logout', '\\'.self::class.'@logout')
             ->name('dashboard.logout.view');
    }
}
