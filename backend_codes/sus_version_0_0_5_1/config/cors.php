<?php

return [
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => [
    'https://smart-university.site', 
    'http://sus_version_0_0_3_1.test', 
    'https://sus_version_0_0_3_1.test'
],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true, // CRITICAL for Sanctum
];