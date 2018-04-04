<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/31/18
 * Time: 12:14 PM
 */
namespace App\Parsers;


class IngNldParser extends BaseClass
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
            'date' => $item['date'],
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
            'BA' => 'pay_terminal',
            'GT' => 'online_banking',
            'OV' => 'transfer',
            'IC' => 'debt_collection',
            'GM' => 'atm',
            'DV' => 'miscellaneous',
        ], $value);
    }
}