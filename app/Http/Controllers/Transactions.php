<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Parsers\BaseClass;

class Transactions extends Controller
{
    public function __invoke(Request $request)
    {
        //return app(BaseClass::class)->findInGooglePlaces('Salon GATTA CH Poznan PL');

        //return app(BaseClass::class)->findMerchant('Salon GATTA CH Poznan PL');
        
        return Transaction::where('user_id', $request->user()->id)->orderBy('date', 'desc')->paginate();

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