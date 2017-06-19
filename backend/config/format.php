<?php

return [

    'frontend' => [

        'url' => [

            'path' => config('main.frontend.url') . '/%s',

            'photo_page' => config('main.frontend.url') . '/photos?show=%s',

            'tag_page' => config('main.frontend.url') . '/photos/tag/%s',

            'unsubscription_page' => config('main.frontend.url') . '/unsubscription/%s',

        ],

    ],

    'storage' => [

        'url' => [

            'path' => config('main.storage.url') . '%s',

        ],

    ],

];
