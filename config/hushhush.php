<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hush-Hush general configs
    |--------------------------------------------------------------------------
    |
    | You can assign database connection to use secret login details
    */

    'database' => [
        'connection' => env('HH_DB_CONNECTION', null),
        'secret'     => env('HH_DB_SECRET', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Hush-Hush general configs
    |--------------------------------------------------------------------------
    |
    | You can assign secrets, and later get them from config
    | Example:
    |
    | 'secret' => [
    |    'name_of_secret' => 'hush-hush-secret',
    |    'name_of_another_secret' => 'hush-hush-super-secret',
    | ],
    |
    | config('hush-hush.secret.name_of_secret')
    */

    'secret' => [
        'name_of_secret' => 'hush-hush-secret',
    ],

    'credentials' => false,
];
