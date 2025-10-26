<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Le disque par défaut utilisé par Laravel. En production, on bascule
    | automatiquement sur S3 via la variable d’environnement FILESYSTEM_DISK.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'persistent'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Ici, on configure tous les disques : local (persistent), public et s3.
    | Le disque S3 sera utilisé uniquement quand FILESYSTEM_DISK=s3
    | dans ton .env de production (Render).
    |
    */

    'disks' => [

        'persistent' => [
            'driver' => 'local',
            'root' => env('STORAGE_PATH', storage_path('app/public')),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'), // optionnel si tu as un endpoint custom
            'endpoint' => env('AWS_ENDPOINT'), // optionnel
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
