<?php

use App\Consts\AllowedCurrencyCodes;
use App\Consts\AllowedTimezones;

return [

    /*
    |--------------------------------------------------------------------------
    | Default currency inside application
    |--------------------------------------------------------------------------
    */
    'currency' => AllowedCurrencyCodes::USD,


    /*
    |--------------------------------------------------------------------------
    | Default timezone
    |--------------------------------------------------------------------------
    */
    'timezone' => AllowedTimezones::CET,

];