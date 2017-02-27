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
                'size' => [
                    'width' => 1500,
                    'height' => 1500,
                ],
            ],
            [
                'mode' => 'inset',
                'size' => [
                    'width' => 600,
                    'height' => 600,
                ],
            ],
        ],
    ],

];
