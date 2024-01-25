<?php

return [
    /**
     * Edgeport API KEY
     */
    'api_key' => env('ADNCACHE_API_KEY', ''),

    /**
     * Edgeport Endpoint
     */
    'endpoint' => env('ADNCACHE_ENDPOINT', 'https://api.edgeport.com/api/v1/wordpress/purge'),

    /**
     * Enable ESI
     */
    'esi' => env('ADNCACHE_ESI_ENABLED', false),

    /**
     * Default cache TTL in seconds
     */
    'default_ttl' => env('ADNCACHE_DEFAULT_TTL', 0),

    /**
     * Default cache storage
     * private,no-cache,public,no-vary
     */
    'default_cacheability' => env('ADNCACHE_DEFAULT_CACHEABILITY', 'no-cache'),

    /**
     * Guest only mode (Do not cache logged in users)
     */
     'guest_only' => env('ADNCACHE_GUEST_ONLY', false),
];
