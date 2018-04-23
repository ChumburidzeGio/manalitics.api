<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;

class TransactionsRefresh
{
    public function __invoke(Request $request)
    {
        $transactions = Transaction::all()->groupBy('bank')->map(function ($group, $bank) {

            $parser = (new BaseClass)->getParser($bank);

            $transactions = $group->map(function ($item) use ($parser) {

                $transaction = $parser->normalizeAndValidate($item->original);

                $item->update($transaction);

            });

        });

        return null;
    }
}