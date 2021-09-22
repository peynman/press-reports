<?php

return [
    'reports' => [
        // store measurements in influxdb database
        'reports_service' => true,
        // store measurements in internal database (eloquent)
        'metrics_table' => true,
    ],

    // batch reporting settings
    'batch' => [
        'connection' => 'default',
        'key' => 'crud.reports.list',
        'max_batch_size' => 1000,
        'batch_interval' => 60000, // milliseconds
    ],

    // influxdb connection
    'influxdb' => [
        'schema' => env('INFLUXDB_SCHEMA', 'http'),
        'host' => env('INFLUXDB_HOST', 'influxdb'),
        'port' => env('INFLUXDB_PORT', '9999'),
        'database' => env('INFLUXDB_DB', 'app'),
        'token' => env('INFLUXDB_TOKEN', ''),
        'org' => env('INFLUXDB_ORG', 'app'),
    ],

    // filters applied to all reports
    'common_filters' => [
        \Larapress\Profiles\Services\DomainMetrics\DomainMetricsProvider::class,
    ],

    // crud resources of the package
    'routes' => [
        'task_reports' => [
            'name' => 'task-reports',
            'model' => \Larapress\Reports\Models\TaskReport::class,
            'provider' => \Larapress\Reports\CRUD\TaskReportsCRUDProvider::class,
        ],
        'metrics' => [
            'name' => 'metrics',
            'model' => \Larapress\Reports\Models\MetricCounter::class,
            'provider' => \Larapress\Reports\CRUD\MetricsCRUDProvider::class,
        ],
        'laravel_echo' => [
            'name' => 'laravel-echo',
            'provider' => \Larapress\Reports\CRUD\LaravelEchoCRUDProvider::class,
        ],
    ],

    'permissions' => [
        \Larapress\Reports\CRUD\MetricsCRUDProvider::class,
        \Larapress\Reports\CRUD\TaskReportsCRUDProvider::class,
        \Larapress\Reports\CRUD\LaravelEchoCRUDProvider::class,
    ],
];
