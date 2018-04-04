<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class Transactions extends Controller
{
    public function __invoke(Request $request)
    {
        return Transaction::where('user_id', $request->user()->id)->orderBy('date', 'desc')->paginate();
    }
}