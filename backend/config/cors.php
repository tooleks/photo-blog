<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP CORS configuration
    |--------------------------------------------------------------------------
    |
    */

    'headers' => [

        'access-control-allow-origin' => env('FRONTEND_APP_URL'),

        'access-control-allow-methods' => 'POST, GET, OPTIONS, PUT, DELETE',

        'access-control-allow-headers' => 'accept, content-type, authorization, x-requested-with, x-xsrf-token, x-csrf-token',

        'access-control-allow-credentials' => 'true',

    ],

];
