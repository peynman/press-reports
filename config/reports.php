<?php

return [
    'grafana' => [
        'base' => env('GRAFANA_API_SCHEMA', 'http').'://'.env('GRAFANA_API_HOST', 'grafana').':'.env('GRAFANA_API_PORT', 3000).'/',
        'auth_token' => env('GRAFANA_API_TOKEN', ''),
        'admin_username' => env('GRAFANA_API_ADMIN_USER', 'grafana'),
        'admin_password' => env('GRAFANA_API_ADMIN_PASS', 'grafanapass'),
        'orgId' => env('GRAFANA_API_ORG_ID', 1)
    ],

    'queue' => [
        'name' => 'listeners',
        'connection' => 'default',
        'delay' => 0
    ],

    'batch' => [
        'connection' => 'default',
        'key' => 'crud.reports.list',
        'max_batch_size' => 1000,
        'batch_interval' => 60000, // milliseconds
    ],

    'influxdb' => [
        'schema' => env('INFLUXDB_SCHEMA', 'http'),
        'host' => env('INFLUXDB_HOST', 'influxdb'),
        'port' => env('INFLUXDB_PORT', '9999'),
        'database' => env('INFLUXDB_DB', 'app'),
        'token' => env('INFLUXDB_TOKEN', ''),
        'org' => env('INFLUXDB_ORG', 'app'),
    ],


    'common_filters' => [
        \Larapress\Profiles\Services\DomainMetircs\DomainMetircsProvider::class,
    ],

    'permissions' => [
        \Larapress\Reports\CRUD\MetricsCRUDProvider::class,
        \Larapress\Reports\CRUD\TaskReportsCRUDProvider::class,
        \Larapress\Reports\CRUD\LaravelEchoCRUDProvider::class,
    ],

    'controllers' => [
        \Larapress\Reports\Controllers\MetricsController::class,
        \Larapress\Reports\Controllers\LaravelEchoController::class,
        \Larapress\Reports\Controllers\TaskReportController::class,
    ],

    'routes' => [
        'task_reports' => [
            'name' => 'task-reports',
            'model' => \Larapress\Reports\Models\TaskReport::class,
            'extend' => [
                'providers' => [
                ]
            ],
        ],
        'metrics' => [
            'name' => 'metrics',
            'model' => \Larapress\ECommerce\Models\CartMetricsCounter::class,
            // 'model' => \Larapress\Reports\Models\MetricCounter::class,
            'extend' => [
                'providers' => [
                    \Larapress\ECommerce\CRUD\ProductMetricsCRUDProvider::class,
                ]
            ],
        ],
        'laravel_echo' => [
            'name' => 'laravel-echo',
            'extend' => [
                'providers' => []
            ],
        ],
    ],
];
