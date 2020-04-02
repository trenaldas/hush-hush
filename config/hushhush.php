<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hush-Hush database connection details
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
    | Hush-Hush secrets storage
    |--------------------------------------------------------------------------
    |
    | You can assign secrets, and later get them from config
    | Example:
    |
    | 'secrets' => [
    |   'name_of_secret' => 'hush-hush-secret',
    ],
    |
    | Get the values of your secrets:
    |--------------------------------------------------------------------------
    | config('hush-hush.show.name_of_secret')
    */

    'secrets' => [
        //
    ],

];
