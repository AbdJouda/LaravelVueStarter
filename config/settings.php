<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Access Codes Defaults
   |--------------------------------------------------------------------------
   */
    'access_codes' => [

       /*
       |-------------------------------------------
       | Reset Code Expiration Time
       |-------------------------------------------
       |
       | Setting the expiration time for password
       | reset code in minutes.
       |
       */
        'reset_password_expiration_' => 180,
    ],

    /*
    |--------------------------------------------------------------------------
    | Api Defaults
    |--------------------------------------------------------------------------
    */
    'api' => [

        /*
        |-------------------------------------------
        | Available API Versions
        |-------------------------------------------
        |
        | List all available api versions. each version
        | must have a separate route folder named with
        | same version
        |
        */
        'versions' => [1],

        /*
        |-------------------------------------------
        | Default API Version
        |-------------------------------------------
        |
        | Current api version will be set through app requests header
        */
        'current_version' => env('CURRENT_API_VERSION', '1'),

        /*
        |--------------------------------------------------------------------------
        | Available locales
        |--------------------------------------------------------------------------
        |
        | List all locales that the application works with.
        | Current used local will be set through requests' header
        |
        */
        'locales' => ['English' => 'en'],

    ],
];
