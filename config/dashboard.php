<?php

return [
    'blade' => [
        'theme' => [
            'name' => null,
            'namespace' => 'larapress-dashboard'
        ],
    ],
    'redirects' => [
        'login' => 'dashboard.any',
        'logout' => 'dashboard.login.view',
        'home' => 'home',
        'signup' => 'dashboard.login.view',
    ],

    'controllers' => [
        'crud' => [

        ],
        'crud-render' => [

        ],
    ],

    'permissions' => [

    ],

    'sidebar' => [
        
    ],
];
