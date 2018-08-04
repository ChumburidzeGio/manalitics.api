<?php

namespace App\Http\Controllers;

use App\Consts\AllowedCurrencyCodes;
use Illuminate\Http\Request;

class Currencies extends Controller
{
    public function __invoke(Request $request)
    {
        return AllowedCurrencyCodes::signs();
    }
}