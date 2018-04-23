<?php

namespace App\Calculators;

class Sum extends BaseClass
{
    public function calculate($transactions)
    {
        $items = $transactions->map(function ($item) {
            return (object) [
                'amount' => $this->currencyFormat($item->amount, $item->currency),
                'is_expense' => $item->is_expense,
            ];
        });

        $expense = $items->where('is_expense', true)->sum('amount');

        $income = $items->where('is_expense', false)->sum('amount');

        $expense = $this->moneyFormat($expense);

        $income = $this->moneyFormat($income);

        return compact('expense', 'income');
    }
}