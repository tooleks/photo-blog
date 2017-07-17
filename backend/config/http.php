<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP CORS configuration
    |--------------------------------------------------------------------------
    |
    */

    'cors' => [

        'allowed_origins' => config('main.frontend.url'),

        'allowed_methods' => 'POST, GET, OPTIONS, PUT, DELETE',

        'allowed_headers' => 'Accept, Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN',

        'allowed_credentials' => 'true',

    ],

];
