<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 4/7/18
 * Time: 1:21 PM
 */

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class StatsGeneral
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
     * Get general stats
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $types = explode(',', $request->input('types', $this->defaultTypes));

        $banks = explode(',', $request->input('banks', $this->defaultBanks));

        $transactions = Transaction::whereIn('type', $types)->whereIn('bank', $banks)->where('user_id', $request->user()->id)->get();

        $transactions = $transactions->map(function ($item) {
            return (object) [
                'month' => "{$item->date->year} {$item->date->format('F')}",
                'amount' => $this->currencyFormat($item->amount, $item->currency),
                'type' => $item->type,
                'is_expense' => $item->is_expense,
            ];
        });

        return $transactions->groupBy('month')->map(function ($items, $month) {

            $expense = $items->where('is_expense', true)->sum('amount');

            $income = $items->where('is_expense', false)->sum('amount');

            $change = $income  - $expense;

            $expense = $this->moneyFormat($expense);

            $income = $this->moneyFormat($income);

            $change = $this->moneyFormat($change);

            return compact('expense', 'income', 'change', 'month');

        })->values()->all();
    }

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