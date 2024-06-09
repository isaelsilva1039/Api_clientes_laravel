<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        '*',
        'http://localhost:3000',
        'http://localhost:8000',
        'https://app.racca.store',
        'https://racca.store',
        'https://raaca-front-git-main-isaels-projects-3d13bff2.vercel.app'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];


