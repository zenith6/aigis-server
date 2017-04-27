<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => true,
    'allowedOrigins'      => [env('CORS_ALLOWED_ORIGINS', '*')],
    'allowedHeaders'      => ['*'],
    'allowedMethods'      => ['*'],
    'exposedHeaders'      => [],
    'maxAge'              => 0,
    'hosts'               => [],
];

