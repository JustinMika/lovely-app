<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Key
    |--------------------------------------------------------------------------
    |
    | This option controls the default session key used to store the shopping cart.
    |
    */

    'session_key' => 'shopping_cart',

    /*
    |--------------------------------------------------------------------------
    | Default Instance
    |--------------------------------------------------------------------------
    |
    | This option controls the default cart instance.
    |
    */

    'default_instance' => 'shopping',

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This option controls the default currency format.
    |
    */

    'format_numbers' => false,

    'decimals' => 0,

    'dec_point' => ',',

    'thousands_sep' => ' ',

    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the storage driver for the shopping cart.
    | Supported: "session", "database"
    |
    */

    'storage' => 'session',

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    |
    | This option controls the database settings when using database storage.
    |
    */

    'database' => [
        'connection' => null,
        'table' => 'shopping_cart',
    ],

];
