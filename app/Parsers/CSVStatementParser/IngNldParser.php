<?php
namespace App\Parsers\CSVStatementParser;

use App\Consts\AllowedTransactionTypes;
use Spatie\Regex\Regex;
use Carbon\Carbon;
class IngNldParser extends CSVStatementParser
{
    protected $bankName = 'ing.nl';

    protected $headersIndex = 1;

    protected $headers = [
        'date', 'title', 'account_id', 'contraccount_id', 'type', 'is_expense', 'amount', 'sort', 'description'
    ];

    /**
     * @param $item
     * @return array
     * @throws \Exception
     */
    public function normalize($item)
    {
        return [
            'title' => $item['title'],
            'date' => $this->extractDate($item),
            'description' => $item['description'],
            'type' => $this->getCode($item['type']),
            'amount' => $item['amount'],
            'currency' => 'EUR',
            'is_expense' => $item['is_expense'] === 'Af',
            'original' => $item,
        ];
    }

    private function getCode($value)
    {
        return array_get([
            'BA' => AllowedTransactionTypes::PAY_TERMINAL,
            'GT' => AllowedTransactionTypes::ONLINE_BANKING,
            'OV' => AllowedTransactionTypes::TRANSFER,
            'IC' => AllowedTransactionTypes::DEBT_COLLECTION,
            'GM' => AllowedTransactionTypes::ATM,
            'DV' => AllowedTransactionTypes::MISCELLANEOUS,
        ], $value);
    }

    private function extractDate($item)
    {
        $regex = Regex::match('/Pasvolgnr:([0-9]){0,3} (.*) Transactie/', $item['description']);

        if($regex->hasMatch()) {
            return $regex->group(2);
        }

        return $item['date'];
    }
}