<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Normalizers\CurrencyNormalizer;

class StatsGeneral
{
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
        $transactions = Transaction::where('user_id', $request->user()->id)->get();

        $transactions = $transactions->map(function ($item) {

            $amount = new CurrencyNormalizer($item->amount, $item->currency);

            return (object) [
                'month' => "{$item->date->year} {$item->date->format('F')}",
                'amount_row' => $amount->result(),
                'amount' => $amount->shape()->result(),
                'type' => $item->type,
                'is_expense' => $item->is_expense,
                'category' => $item->category,
            ];
        });

        $change = $transactions->groupBy('month')->map(function ($items, $month) {

            $expense = $this->norm(
                $items->where('is_expense', true)->sum('amount_row')
            );

            $income = $this->norm(
                $items->where('is_expense', false)->sum('amount_row')
            );

            $change = $this->norm($items->sum('amount_row'));

            return compact('expense', 'income', 'change', 'month');

        })->values()->all();

        $categories = $transactions->groupBy('category')->map(function ($items, $category) {

            $expense = $this->norm(
                $items->where('is_expense', true)->sum('amount_row')
            );

            return compact('expense', 'category');

        })->values()->all();

        return compact('change', 'categories');
    }

    protected function norm($amount)
    {
        return (new CurrencyNormalizer($amount))->shape()->result();
    }
}