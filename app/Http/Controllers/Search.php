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
        
        return $transations->orderBy('date', 'desc')->limit(50)->get();
    }
}