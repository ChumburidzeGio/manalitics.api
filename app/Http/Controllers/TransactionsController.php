<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;
use App\Normalizers\MoneyNormalizer;
use App\Parsers\CSVStatementParser\CSVStatementParser;

class TransactionsController extends Controller
{
    public function all(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)->orderBy('date', 'desc')->simplePaginate();

        return $transactions->getCollection()->transform(function($item) {
            $original = $item->only(['id', 'title', 'category', 'currency']);

            return array_merge($original, [
                'amount' => MoneyNormalizer::toCash($item->amount),
                'date' => $item->date->format('c'),
            ]);
        })->all();
    }

    public function find(Request $request)
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

    public function update(Request $request)
    {
        $transaction = Transaction::find($request->id);

        $transaction->update($request->only(['category']));

        return $transaction;
    }

    public function search(Request $request)
    {
        $transations = Transaction::where('user_id', $request->user()->id);

        if($request->has('query')) {
            $query = strtolower($request->input('query'));
            $transations->where('title', 'like', "%$query%")->orWhere('description', 'like', "%$query%");
        }
        
        return $transations->orderBy('date', 'desc')->limit(50)->get();
    }

    /**
     * Import transactions from file
     *
     * @param Request $request
     * @return mixed
     */
    public function import(Request $request)
    {        
        $this->validate($request, [
            'bank' => 'required',
            'file' => 'required|file'
        ]);

        return (new CSVStatementParser)->parse(
            $request->file('file')->path(),
            $request->user(),
            $request->bank
        );
    }
}