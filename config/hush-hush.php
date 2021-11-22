<?php

return [

    /*
    |----------------------------------------------------------------------------
    | Your project environments
    |----------------------------------------------------------------------------
    */

    'environments' => [
        'staging',
        'production',
    ],

    /*
    |----------------------------------------------------------------------------
    | Throw exception if hush hush fails to get or set secret for db credentials.
    | If set to false - exception will be echoed only.
    |----------------------------------------------------------------------------
    */
    'exception_throw' => env('HUSH_HUSH_THROW_EXCEPTION', false),

    /*
    |----------------------------------------------------------------------------
    | Get secret from SM with every request.
    | If set to false - checks if db connection is okay,
    | if not - tries to get secret.
    |----------------------------------------------------------------------------
    */
    'every_request' => env('HUSH_HUSH_THROW_EXCEPTION', false),
];
