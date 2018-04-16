<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;
use Carbon\Carbon;

class Search extends Controller
{
    public function __invoke(Request $request)
    {
        $transations = Transaction::where('user_id', $request->user()->id);

        if($request->has('query')) {
            $query = strtolower($request->input('query'));
            $transations->where('title', 'like', "%$query%")->orWhere('description', 'like', "%$query%");
        }

        if($request->has('from')) {
            $transations->whereDate('date', '>', Carbon::parse($request->from));
        }

        if($request->has('to')) {
            $transations->whereDate('date', '<', Carbon::parse($request->to));
        }

        if($request->has('bank')) {
            $transations->where('bank', 'LIKE', "%{$request->bank}%");
        }
        
        return $transations->orderBy('date', 'desc')->limit(50)->get();
    }
}