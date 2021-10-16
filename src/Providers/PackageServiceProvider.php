<?php

namespace Larapress\Reports\Providers;

use Illuminate\Support\ServiceProvider;
use Larapress\Reports\Commands\BatchReportInfluxDB;
use Larapress\Reports\Commands\BatchReportPurge;
use Larapress\Reports\Commands\FillMetricNumbers;
use Larapress\Reports\Commands\GrabEchoStats;
use Larapress\Reports\Commands\QueueScheduledTasks;
use Larapress\Reports\Services\Reports\IReportsService;
use Larapress\Reports\InfluxDB\InfluxDBReportService;
use Larapress\Reports\Services\LaravelEcho\ILaravelEchoMetrics;
use Larapress\Reports\Services\Reports\IMetricsService;
use Larapress\Reports\Services\LaravelEcho\LaravelEchoMetrics;
use Larapress\Reports\Services\Reports\MetricsService;
use Larapress\Reports\Services\TaskScheduler\ITaskSchedulerService;
use Larapress\Reports\Services\TaskScheduler\TaskSchedulerService;

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
        $this->app->bind(ITaskSchedulerService::class, TaskSchedulerService::class);
        $this->app->bind(IMetricsService::class, MetricsService::class);
        $this->app->bind(ILaravelEchoMetrics::class, LaravelEchoMetrics::class);
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
                BatchReportPurge::class,
                BatchReportInfluxDB::class,
                GrabEchoStats::class,
                QueueScheduledTasks::class,
                FillMetricNumbers::class,
            ]);
        }
    }
}
