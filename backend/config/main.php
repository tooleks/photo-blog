<?php

return [

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
