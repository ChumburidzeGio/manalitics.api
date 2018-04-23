<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;

class TransactionDetails
{
    public function __invoke(Request $request)
    {
        return Transaction::where('user_id', $request->user()->id)->find($request->id);
    }
}