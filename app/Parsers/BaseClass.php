<?php

namespace App\Parsers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Regex\Regex;
use App\Models\Account;
use App\Models\Transaction;
//use SKAgarwal\GoogleApi\PlacesApi;
use App\Consts\AllowedTransactionTypes;
use App\Consts\AllowedBanks;

class BaseClass
{
    protected $bankName;

    protected $encoding;

    protected $delimiter;

    protected $enclosure;

    protected $dateFormat;

    protected $headersIndex;

    protected $headersStartsWith;

    protected $headers;

    /**
     * @param $file
     * @return array
     */
    public function parse($file)
    {
        $cells = $this->read($file);

        return $this->normalizeAndFilterAll($cells);
    }

    /**
     * @param $bank
     * @return \Laravel\Lumen\Application|mixed
     */
    public function getParser($bank)
    {
        $class = array_get([
            AllowedBanks::INGPL => IngPolParser::class,
            AllowedBanks::INGNL => IngNldParser::class,
            AllowedBanks::TBCBANK => TbcBankParser::class,
        ], $bank, BaseClass::class);

        return app($class);
    }

    /**
     * @param $file
     * @return array
     */
    public function import($file, $user)
    {
        $transactions = $this->parse($file);

        return $transactions->map(function($input) use ($user) {

            $transaction = Transaction::where('user_id', $user->id)
                ->where('original', json_encode($input['original']))->first();

            if(!$transaction)
            {
                $account = $this->getAccount($user);

                $input = array_merge(array_only($input, [
                    'title', 'date', 'description', 'amount', 'type', 
                    'currency', 'is_expense', 'user_id', 'original'
                ]), [
                    'user_id' => $user->id,
                    'account_id' => $account->id
                ]);
                $transaction = Transaction::create($input);
            }

            return $transaction;
        });
    }

    public function getAccount($user) : Account
    {
        $name = array_get(AllowedBanks::names(), $code, 'Default');

        return Account::firstOrCreate([
            'name' => $name,
            'user_id' => $user->id
        ]);
    }

    public function validTransaction($transaction) : bool
    {
        if(!array_has($transaction, [
            'title',
            'date',
            'description',
            'amount',
            'type',
            'currency',
            'is_expense',
            'original'
        ])) {
            return false;
        }

        if(AllowedTransactionTypes::is_invalid($transaction['type']))
        {
            return false;
        }
        
        return true;
    }

    /**
     * @param $cells
     * @return mixed
     */
    public function normalizeAndFilterAll($cells)
    {
        $transactions = $cells->map(function ($item) {

            return $this->normalizeAndValidate($item);
        });

        return $transactions->filter();
    }

    /**
     * @param $item
     * @return mixed
     */
    public function normalizeAndValidate($item)
    {
        $normalized = $this->normalize($item);

        if(!$normalized) {
            return null;
        }

        $normalized = array_merge($normalized, [
            'amount' => intval(($normalized['is_expense'] ? '-' : '') . str_replace(',', '', $normalized['amount'])),
            'date' => $this->getDate($normalized['date'], $this->dateFormat),
        ]);
        
        if($this->bankName){
            $normalized['original']['bank'] = $this->bankName;
        }

        if($this->validTransaction($normalized)) {
            return array_only($normalized, [
                'title', 'date', 'description', 'amount',
                'type', 'currency', 'is_expense', 'original'
            ]);
        }

        return null;
    }

    /**
     * @param $file
     * @return \Illuminate\Support\Collection
     */
    public function read($file)
    {
        $reader = IOFactory::createReaderForFile($file);

        if($this->encoding) {
            $reader->setInputEncoding($this->encoding);
        }

        if($this->delimiter) {
            $reader->setDelimiter($this->delimiter);
        }

        if($this->enclosure) {
            $reader->setEnclosure($this->enclosure);
        }

        $spreadsheet = $reader->load($file);

        $cells = $spreadsheet->getSheet($this->sheetIndex ?? 0)->toArray(null, true, true, true);

        if($this->headers || $this->headersIndex || $this->headersStartsWith) {
            return $this->setHeaders($cells);
        }

        return collect($cells);
    }

    /**
     * @param $cells
     * @return \Illuminate\Support\Collection
     */
    private function setHeaders($cells)
    {
        $index = $this->headersIndex;

        $startsWith = $this->headersStartsWith;

        $headers = $this->headers ?? [];

        $transactions = [];

        foreach ($cells as $key => $cell)
        {
            if(!is_null($index))
            {
                if($key === $index && !count($headers)) {
                    $headers = $cell;
                }

                if($key <= $index) {
                    continue;
                }
            }

            if(!is_null($startsWith))
            {
                if(head($cell) === $startsWith)
                {
                    if(!count($headers)) {
                        $headers = $cell;
                    }

                    $transactions = [];

                    continue;
                }
            }

            $transactions[] = array_combine($headers, array_slice($cell, 0, count($headers)));
        }

        return collect($transactions);
    }

    /**
     * @param $value
     * @param null $format
     * @return string
     */
    public function getDate($value, $format = null)
    {
        $value = $format ?
            Carbon::createFromFormat($format, $value) :
            Carbon::parse($value);

        return $value->format('Y-m-d H:i:s');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getPosId($value)
    {
        return preg_replace('/\s+/', ' ', trim($value, '" '));
    }

    // /**
    //  * @param $value
    //  * @return mixed
    //  */
    // public function unifyPosId($value)
    // {
    //     $replacers = [
    //         '/(uber|HELPUBER)/' => 'UBER',
    //         '/(SHOPIFY)/' => 'SHOPIFY',
    //         '/(FACEBK)/' => 'FACEBOOK ADS',
    //         '/(Albert Heijn|AH TO GO|AH Campus Diemen)/i' => 'ALBERT HEIJN',
    //         '/(Wizz Air)/' => 'WIZZ AIR',
    //         '/(Vomar Geuzenpoort)\s+([A-Z_0-9]+)/' => 'VOMAR',
    //         '/(DIRK VDBROEK)\s+([A-Z_0-9 ]+)/' => 'DIRK VAN DEN BROEK',
    //         '/(BIEDRONKA)/' => 'BIEDRONKA',
    //     ];

    //     foreach ($replacers as $pattern => $replacer)
    //     {
    //         if(Regex::matchAll($pattern, $value)->hasMatch()) {
    //             $value = $replacer;
    //         }
    //     }

    //     $value = preg_replace("/[^A-Za-z ]/", '', $value);

    //     //Remove all words shorter then 3 chars
    //     //$value = preg_replace("/(\b\w{1,2}\b\s?)/", '', $value);

    //     $value = $this->iReplace([
    //         'POZNAN POZNA' => 'POZNAN PL',
    //         'POZNA POZNAN' => 'POZNAN PL',
    //         'PL PL' => 'PL',
    //     ], $value);

    //     $value = $this->getPosId($value);

    //     return strtoupper($value);
    // }

    // /**
    //  * @param $replacements
    //  * @param $value
    //  * @return mixed
    //  */
    // public function iReplace($replacements, $value)
    // {
    //     foreach ($replacements as $pattern => $replacement) {
    //         $value = str_ireplace($pattern, $replacement, $value);
    //     }

    //     return $value;
    // }

    // /**
    //  * @param $value
    //  * @return mixed
    //  */
    // public function unifyType($value)
    // {
    //     $type = null;

    //     $matchers = [
    //         '/(BAR|Cafe)/i' => 'food',
    //         '/(HOTEL)/' => 'hotel',
    //         '/(APTEKA|PHARMACY)/' => 'pharmacy',
    //     ];

    //     foreach ($matchers as $pattern => $match)
    //     {
    //         if(Regex::matchAll($pattern, $value)->hasMatch()) {
    //             $type = $match;
    //         }
    //     }

    //     return $type;
    // }

    // /**
    //  * @param $name
    //  * @return mixed
    //  */
    // public function findInGooglePlaces($name)
    // {
    //     $googlePlaces = new PlacesApi($this->getRandomApiKey());

    //     $response = $googlePlaces->textSearch($name);

    //     $results = array_get($response, 'results');

    //     if(!$results) {
    //         return $response;
    //     }

    //     $first = array_first($results);

    //     $photoReference = array_get(array_first(array_get($first, 'photos')), 'photo_reference');

    //     $photoSrc = $photoReference ? 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$photoReference.'&sensor=false&key='.$this->getRandomApiKey() : null;

    //     return [
    //         'name' => array_get($first, 'name'),
    //         'id' => array_get($first, 'place_id'),
    //         'photo_reference' => $photoReference,
    //         'photo_src' => $photoSrc,
    //         //'photo_blob' => $photoSrc ? addslashes(file_get_contents($photoSrc)) : $photoSrc,
    //         'types' => array_get($first, 'types'),
    //         'original' => $results,
    //     ];
    // }

    // private function getRandomApiKey()
    // {
    //     return array_random($this->placeApiKeys);
    // }
}