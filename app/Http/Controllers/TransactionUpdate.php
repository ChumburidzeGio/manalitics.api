<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;

class TransactionUpdate extends Controller
{
    public function __invoke(Request $request)
    {
        $transaction = Transaction::find($request->id);

        $transaction->update($request->only(['category']));

        return $transaction;
    }
}