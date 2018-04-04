<?php

namespace App\Http\Controllers\Channels;

use App\Http\Controllers\Controller;
use App\Parsers\BaseClass;
use App\Parsers\IngPolParser;
use App\Parsers\IngNldParser;
use App\Parsers\TbcBankParser;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;
use Mockery\Exception;
use Illuminate\Support\Facades\Cache;
use App\Models\Transaction;
use App\Classifiers\PlaceClassifier;

class IngImportFromFile extends Controller
{
    /**
     * Get the property types
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return app(PlaceClassifier::class)->process($request->place);

//        return app('validator')->make([
//            'type' => 'online_banking',
//            'bank' => 'tbcbank',
//            "description" => "Beeline;591815010;თანხა:3.00",
//        ], [
//            'type' => 'in:online_banking',
//            'bank' => 'in:tbcbank',
//            'description' => 'regex:/([a-zA-Z0-9]+);([0-9]){9};(თანხა:([0-9.]+))/'
//        ])->passes();

        $parser = $this->getParser($request->bank);

        $transactions = $parser->parse($request->file('csv')->path());

        if($request->has('debug') && $request->debug)
        {
            dd($transactions);
        }

        return $transactions;
    }

    private function getParser($bank)
    {
        $class = array_get([
            'ing.pl' => IngPolParser::class,
            'ing.nl' => IngNldParser::class,
            'tbcbank' => TbcBankParser::class,
        ], $bank, BaseClass::class);

        return app($class);
    }

//    private function proccessDescription($value)
//    {
//        $data = [];
//
//        preg_match_all('/([A-Za-z]+:)/', $value, $matches);
//
//        $headers = head($matches);
//
//        foreach ($headers as $key => $header)
//        {
//            $endPosition = strpos($value, $header) + strlen($header);
//
//            $next = isset($headers[$key+1]) ? $headers[$key+1] : null;
//
//            $nextStartPosition = is_null($next) ? strlen($value) : strpos($value, $next);
//
//            $data[$header] = substr($value, $endPosition, ($nextStartPosition - $endPosition));
//        }
//    }
//
//    private function proccessName($value)
//    {

//
//        if(in_array('health', $types))
//        {
//            return 'health';
//        }
//
//        if(in_array('restaurant', $types) || in_array('bar', $types))
//        {
//            return 'bar/restaurant';
//        }
//
//        if(in_array('lodging', $types))
//        {
//            return 'lodging';
//        }
//
//        if(in_array('car_rental', $types))
//        {
//            return 'car_rental';
//        }
//
//        if(in_array('zoo', $types))
//        {
//            return 'zoo/museum';
//        }
//
//        if(in_array('transit_station', $types))
//        {
//            return 'transportation';
//        }
//
//        if(in_array('supermarket', $types))
//        {
//            return 'supermarket';
//        }
//
//        return $types;
//    }
}