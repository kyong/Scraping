<?php
return [
    'credentials' => [
        'key'    => env('YOUR_AWS_ACCESS_KEY_ID'),
        'secret' => env('YOUR_AWS_SECRET_ACCESS_KEY'),
    ],
    'region' => env('AWS_REGION'),
    'version' => 'latest',
    
    // You can override settings for specific services
    'Ses' => [
        'region' => 'ap-northeast-1',
    ],
];