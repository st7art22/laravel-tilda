<?php

return [
    'path' => [
        'js' => env('TILDA_JS_PATH', ''),
        'css' => env('TILDA_IMG_PATH', ''),
        'img' => env('TILDA_IMG_PATH', ''),
    ],
    'url' => [
        'public_key' => env('TILDA_API_PUBLIC', ''),
        'private_key' => env('TILDA_API_SECRET', ''),
        'api_url' => env('TILDA_API_URL', ''),
        'api_ver' => env('TILDA_API_VER', ''),
    ],
];