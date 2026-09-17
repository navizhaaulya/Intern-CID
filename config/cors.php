<?php

return [
    'paths' => ['api/*', 'banner', 'no-auth/*', '*'],
    // atau simpel: 'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173'],
    // untuk lebih fleksibel saat dev, bisa pakai pattern:
    // 'allowed_origins_patterns' => ['#^http://localhost:\d+$#'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // set true kalau pakai cookie/sanctum
];