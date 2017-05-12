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
        'photos' => 'photos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Application Configuration
    |--------------------------------------------------------------------------
    |
    */

    'frontend' => [
        'url' => env('FRONTEND_APP_URL', 'http://localhost:8080'),
        'unsubscribe_url' => env('FRONTEND_APP_UNSUBSCRIBE_URL', 'http://localhost:8080/unsubscription'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    */

    'upload' => [
        'min-size' => 1, // kilobytes
        'max-size' => 20480, // kilobytes
        'min-image-width' => 600, // pixels
        'min-image-height' => 600, // pixels
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
                'quality' => 95,    // percentage
                'width' => 1500,     // pixels
                'height' => 1500,    // pixels
            ],
            [
                'mode' => 'inset',
                'quality' => 70,    // percentage
                'width' => 600,     // pixels
                'height' => 600,    // pixels
            ],
        ],
    ],

];
