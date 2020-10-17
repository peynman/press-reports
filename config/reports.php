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

    'routes' => [
        'task_reports' => [
            'name' => 'task-reports',
        ]
    ]
];
