<?php

namespace App\Calculators;

class BaseClass
{
    protected $rates = [
        'PLN/EUR' => '0.25',
        'USD/EUR' => '0.81',
        'GEL/EUR' => '0.34',
    ];

    protected $currency = 'EUR';

    protected $defaultTypes = 'pay_terminal,atm,transfer,debt_collection,miscellaneous,online_banking';

    protected $defaultBanks = 'ing.nl,ing.pl,tbcbank';
    
    /**
     * Format the amount using the application default currency
     *
     * @param $amount
     * @param $currency
     * @return float
     */
    protected function currencyFormat($amount, $currency)
    {
        if($currency === $this->currency) {
            return $amount;
        }

        $exchangeKey = "$currency/$this->currency";

        $exchange = $this->rates[$exchangeKey];

        return $amount * $exchange;
    }

    /**
     * Format the money
     *
     * @param $amount
     * @param $currency
     * @return float
     */
    protected function moneyFormat($amount)
    {
        $formated = number_format($amount, 2);

        if(starts_with($formated, '-')) {
            return '- €' . str_replace('-', '', $formated);
        }

        return '€' . $formated;
    }
}