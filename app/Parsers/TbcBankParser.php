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
        $type = $this->getType($item['Op. Code'], $item['Description']);

        try {
            list($amount, $currency, $is_expenses) = $this->getAmount($item, $type);
        } catch (\Exception $e) {
            return null;
        }

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

    private function getAmount($item, $type)
    {
        $currency = 'GEL';
        $is_expenses = true;
        $amount = $item['Paid Out'];

        if($item['Paid In']) {
            $is_expenses = false;
            $amount = $item['Paid In'];
        }

        if($type === 'pay_terminal') {
            $currency = Regex::match('/თანხა\s[0-9.]+\s([A-Z]{3})/', $item['Description'])->group(1);
        }

        return [$amount, $currency, $is_expenses];
    }

    private function getType($code, $description)
    {
        if($code === 'ISSTR' && starts_with($description, 'POS'))
        {
            return AllowedTransactionTypes::PAY_TERMINAL;
        }

        if($code === 'ISSTR' && starts_with($description, 'ATM CASH'))
        {
            return AllowedTransactionTypes::ATM;
        }

        return array_get([
            //'*COL*' => 'miscellaneous',
            //'*PCC*' => 'miscellaneous',
            //'TBCPA' => 'miscellaneous',
            'P2PTR' => AllowedTransactionTypes::ONLINE_BANKING,
            '*IBS*' => AllowedTransactionTypes::ONLINE_BANKING,
            //'GMI' => 'miscellaneous',
            '*MBS*' => AllowedTransactionTypes::ONLINE_BANKING,
            //'ADJUO' => 'miscellaneous', //adjustment
            //'FEE' => 'miscellaneous', //Fees like atm cash withdrawing fee
            'GIB' => AllowedTransactionTypes::TRANSFER,
            'SWIFT' => AllowedTransactionTypes::TRANSFER, //swift transfer f.e. salary transfer
        ], $code, AllowedTransactionTypes::MISCELLANEOUS);
    }
}