<?php

namespace App\Parsers;

use Spatie\Regex\Regex;

class TbcBankParser extends BaseClass
{
    protected $bankName = 'tbcbank';

    protected $dateFormat = 'd/m/Y';

    protected $sheetIndex = 1;

    protected $headersIndex = 2;

    public function normalize($item)
    {
        try {
            list($amount, $currency, $is_expenses) = $this->getAmount($item);
        } catch (\Exception $e) {
            return null;
        }

        $type = $this->getType($item['Op. Code'], $item['Description']);

        return [
            'title' => $this->getDescription($item['Description'], $type),
            'date' => $item['Date'],
            'description' => $item['Additional Information'],
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'is_expense' => $is_expenses,
            'original' => $item,
        ];
    }

    private function getDescription($value, $type)
    {
        if($type === 'pay_terminal') {
             return Regex::match('/POS([-\s])+(.*), ბარათი(.*)/', $value)->group(2);
        }

        return $value;
    }

    private function getAmount($item)
    {
        $is_expenses = true;
        $amount = $item['Paid Out'];

        if($item['Paid In']) {
            $is_expenses = false;
            $amount = $item['Paid In'];
        }

        return [$amount, 'GEL', $is_expenses];
    }

    private function getType($code, $description)
    {
        if($code === 'ISSTR' && starts_with($description, 'POS'))
        {
            return 'pay_terminal';
        }

        if($code === 'ISSTR' && starts_with($description, 'ATM CASH'))
        {
            return 'atm';
        }

        return array_get([
            '*COL*' => 'miscellaneous',
            '*PCC*' => 'miscellaneous',
            'TBCPA' => 'miscellaneous',
            'P2PTR' => 'online_banking',
            '*IBS*' => 'online_banking',
            'GMI' => 'miscellaneous',
            '*MBS*' => 'online_banking',
            'ADJUO' => 'miscellaneous', //adjustment
            'FEE' => 'miscellaneous', //Fees like atm cash withdrawing fee
            'GIB' => 'transfer',
            'SWIFT' => 'transfer', //swift transfer f.e. salary transfer
        ], $code, 'miscellaneous');
    }
}