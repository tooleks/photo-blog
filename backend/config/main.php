<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Repository Configuration
    |--------------------------------------------------------------------------
    |
    */

    'repository' => [
        'url' => 'https://github.com/tooleks/photo-blog',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation Configuration
    |--------------------------------------------------------------------------
    |
    */

    'documentation' => [
        'rest_api' => [
            'url' => env('API_DOCUMENTATION_URL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    |
    */

    'storage' => [
        'url' => env('STORAGE_URL', env('APP_URL') . '/' . 'storage'),
        'photos' => 'public/photos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Application Configuration
    |--------------------------------------------------------------------------
    |
    */

    'frontend' => [
        'url' => env('FRONTEND_APP_URL', 'http://localhost:8080/'),
        'unsubscribe_url' => env('FRONTEND_APP_UNSUBSCRIBE_URL', 'http://localhost:8080/subscription/unsubscribe/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    */

    'upload' => [
        'min-size' => '1', // kilobytes
        'max-size' => '20480', // kilobytes
    ],

    /*
    |--------------------------------------------------------------------------
    | Photo Configuration
    |--------------------------------------------------------------------------
    |
    */

    'photo' => [
        'thumbnails' => [
            [
                'mode' => 'inset',
                'quality' => 100,    // percentage
                'width' => 1500,     // pixels
                'height' => 1500,    // pixels
            ],
            [
                'mode' => 'inset',
                'quality' => 65,    // percentage
                'width' => 600,     // pixels
                'height' => 600,    // pixels
            ],
        ],
    ],

];
