<?php

return [
    'path' => [
        'js' => env('TILDA_JS_PATH', ''),
        'css' => env('TILDA_IMG_PATH', ''),
        'img' => env('TILDA_CSS_PATH', ''),
    ],
    'url' => [
        'public_key' => env('TILDA_API_PUBLIC', ''),
        'secret_key' => env('TILDA_API_SECRET', ''),
        'api_url' => env('TILDA_API_URL', 'http://api.tildacdn.info/'),
        'api_ver' => env('TILDA_API_VER', 'v1'),
    ],
];