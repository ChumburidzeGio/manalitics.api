<?php

namespace App\Http\Controllers;

use App\Consts\AllowedCurrencyCodes;
use App\Consts\AllowedBanks;
use App\Consts\AllowedCategories;
use App\Consts\AllowedTransactionTypes;

class ConstsController extends Controller
{
    public function currencies()
    {
        return AllowedCurrencyCodes::signs();
    }

    public function banks()
    {
        return AllowedBanks::names();
    }

    public function categories()
    {
        return AllowedCategories::names();
    }

    public function transactionTypes()
    {
        return AllowedTransactionTypes::names();
    }
}