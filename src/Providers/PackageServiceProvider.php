<?php

namespace Larapress\Reports\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Reports\Services\IReportsService;
use Larapress\Reports\Commands\ReportsCommands;
use Larapress\Reports\InfluxDB\InfluxDBReportService;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IReportsService::class, InfluxDBReportService::class);
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

        $this->publishes([
            __DIR__.'/../../config/reports.php' => config_path('larapress/reports.php'),
        ], ['config', 'larapress', 'larapress-reports']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ReportsCommands::class,
            ]);
        }

        // $this->app->booted(function () {
        //     $schedule = app(Schedule::class);
        //     $schedule->command('foo:bar')->everyMinute();
        // });
    }
}
