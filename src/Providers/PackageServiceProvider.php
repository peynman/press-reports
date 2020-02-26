<?php

namespace Larapress\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\CRUDRender\Base\BaseCRUDBladeRenderProvider;
use Larapress\CRUDRender\Base\ICRUDBladeViewProvider;
use Larapress\CRUDRender\Base\ICRUDRenderProvider;
use Larapress\Dashboard\Rendering\VueCRUDViewProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $bladeRendererProvider = function ($app, $params) {
            if (isset($params['metadata'])) {
                $metadata = call_user_func([$params['metadata'], 'instance']);
                if (!is_null($metadata)) {
                    $blade = new VueCRUDViewProvider($metadata);

                    $bladeRenderer = new BaseCRUDBladeRenderProvider();
                    $bladeRenderer->useViewDataProvider($blade);
                    $bladeRenderer->useBladeViewProvider($blade);

                    return $bladeRenderer;
                }
            }

            return null;
        };

        $this->app->bind(ICRUDBladeViewProvider::class, $bladeRendererProvider);
        $this->app->bind(ICRUDRenderProvider::class, $bladeRendererProvider);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'larapress');
        $this->loadMigrationsFrom(__DIR__.'/../../migrations');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'larapress-dashboard');

        $this->publishes([
            __DIR__.'/../../config/dashboard.php' => config_path('larapress/dashboard.php'),
        ], ['config', 'larapress', 'larapress-dashboard']);

        $this->publishes([
            __DIR__.'/../../resources/dist' => storage_path('app/public/vendor/larapress-dashboard'),
        ], ['assets', 'larapress', 'larapress-dashboard']);
    }
}
