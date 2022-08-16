<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Geocode Service
    |--------------------------------------------------------------------------
    |
    | Here you can configure the geocoding service for your application, by
    | default, the OpenStreet Geocoding API is used. This offers a range
    | of different possibilities and is free to use for the beginning.
    |
    | Supported Drivers: "google", "openstreet"
    |
    */

    'driver' => env('GEOCODE_DRIVER', 'openstreet'),
    'api_key' => env('GEOCODE_API_KEY', ''),
];
