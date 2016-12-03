<?php

return [

    'website' => [
        'name' => '',
        'url' => '/',
    ],

    'storage' => [
        'photos' => 'public/photos',
    ],

    'upload' => [
        'min-size' => '1', // kilobytes
        'max-size' => '20480', // kilobytes
    ],

    'photo' => [
        'thumbnails' => [
            [
                'type' => 'zoomCrop',
                'size' => [
                    'width' => 500,
                    'height' => 500,
                ],
            ],
        ],
    ],

];
