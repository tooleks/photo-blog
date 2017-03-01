<?php

return [

    'repository' => [
        'url' => 'https://github.com/tooleks/photo-blog',
    ],

    'documentation' => [
        'api' => [
            'url' => env('API_DOCUMENTATION_URL'),
        ],
    ],

    'storage' => [
        'url' => env('STORAGE_URL', env('APP_URL') . '/' . 'storage'),
        'photos' => 'public/photos',
    ],

    'upload' => [
        'min-size' => '1', // kilobytes
        'max-size' => '20480', // kilobytes
    ],

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
