<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | Driver broadcast default yang akan dipakai (sesuai .env).
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Daftar koneksi broadcast yang tersedia.
    |
    */

    'connections' => [

        'pusher' => [
            'driver'   => 'pusher',
            'key'      => env('PUSHER_APP_KEY'),
            'secret'   => env('PUSHER_APP_SECRET'),
            'app_id'   => env('PUSHER_APP_ID'),
            'options'  => [
                // cluster ap1 untuk Asia
                'cluster'   => env('PUSHER_APP_CLUSTER'),
                'useTLS'    => true,
            ],
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => env('BROADCAST_REDIS_CONNECTION', 'default'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

    

];
