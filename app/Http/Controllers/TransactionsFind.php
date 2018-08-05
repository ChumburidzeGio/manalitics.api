<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;
use App\Normalizers\MoneyNormalizer;

class TransactionsFind extends Controller
{
    public function __invoke(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)->find($request->id);

        $original = $transactions->only([
            'id', 'title', 'category', 'currency', 'description', 'type', 'category_id', 'amount'
        ]);

        return array_merge($original, [
            'amount_formated' => MoneyNormalizer::toCash($transactions->amount),
            'date' => $transactions->date->format('c'),
        ]);
    }
}