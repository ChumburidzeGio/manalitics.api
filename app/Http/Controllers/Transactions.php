<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;
use App\Normalizers\MoneyNormalizer;

class Transactions extends Controller
{
    public function __invoke(Request $request)
    {
        //return app(BaseClass::class)->findInGooglePlaces('Salon GATTA CH Poznan PL');

        $transactions = Transaction::where('user_id', $request->user()->id)->orderBy('date', 'desc')->simplePaginate();

        return $transactions->getCollection()->transform(function($item) {
            $original = $item->only(['id', 'title', 'category', 'currency']);

            return array_merge($original, [
                'amount' => MoneyNormalizer::toCash($item->amount),
                'date' => $item->date->format('c'),
            ]);
        })->all();

//        return Transaction::where('type', 'pay_terminal')->get()->map(function ($item) {
//            return array_merge($item->toArray(), [
//                'postIdFiltered' => $this->getPosId($item->title),
//                'typeFiltered' => $this->getType($this->getPosId($item->title)),
//                'original' => $item->original,
//            ]);
//        })->values()->all();
    }

    public function getPosId($value)
    {
        return (new BaseClass)->unifyPosId($value);
    }

    public function getType($value)
    {
        return (new BaseClass)->unifyType($value);
    }
}