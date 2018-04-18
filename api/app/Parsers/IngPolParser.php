<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/31/18
 * Time: 12:14 PM
 */
namespace App\Parsers;

class IngPolParser extends BaseClass
{
    protected $bankName = 'ing.pl';

    protected $encoding = 'CP1250';

    protected $delimiter = ';';

    protected $enclosure = '';

    protected $headersStartsWith = 'Data transakcji';

    protected $headers = [
        'date', 'posting_date', 'title', 'description', 'account_number',
        'bank_name', 'details', 'transaction_id', 'local_amount', 'local_currency',
        'blocked_amount', 'blocked_amount_currency', 'payment_in_currency', 'payment_currency'
    ];

    /**
     * @param $item
     * @return array
     * @throws \Exception
     */
    public function normalize($item)
    {
        $type = $this->getType($item);

        try {
            list($amount, $currency, $is_expenses) = $this->getAmountAndCurrency($item);
        } catch (\Exception $e) {
            return null;
        }

        $description = trim($item['description'].', Nr transakcji:'.trim($item['transaction_id'], "'"));

        $title = $this->getPosId($item['title']);

        if($type == 'transfer') {
            $title = trim(array_first(explode(',', $title)));
        }

        return [
            'title' => $title,
            'date' => $item['date'],
            'description' => $description,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'is_expense' => $is_expenses,
            'original' => $item,
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Exception
     */
    private function getAmountAndCurrency($item)
    {
        if(!$item['local_amount'])
        {
            if(!$item['payment_in_currency']) {
                throw new \Exception('Money blocking transaction');
            }

            $amount = $item['payment_in_currency']; //Kwota płatności w walucie
            $currency = $item['payment_currency'];
        }
        else
        {
            $amount = $item['local_amount'];
            $currency = $item['local_currency'];
        }

        $is_expenses = starts_with($amount, '-');

        //remove minus and format amount
        $amount = str_replace('-', '', $amount);

        return [$amount, $currency, $is_expenses];
    }

    /**
     * @param $item
     * @return string
     */
    private function getType($item)
    {
        $description = str_slug($item['description']);
        $details = str_slug($item['details']);

        $type = 'miscellaneous';

        if(str_contains($description, 'platnosc-karta')) {
            $type = 'pay_terminal';
        }

        if(str_contains($description, 'wyplata-gotowki')) {
            $type = 'atm';
        }

        if(str_contains($details, 'przelew')) {
            $type = str_contains($description, 'przelew-wlasny') ? 'transfer' : 'online_banking';
        }

        if(str_contains($details, 'stzlec')) {
            $type = 'debt_collection';
        }

        return $type;
    }

//    /**
//     * @param $data
//     * @return mixed
//     * @throws \Exception
//     */
//    private function getAccountId($data)
//    {
//        $accounts = [];
//
//        //Find the account number(s)
//        foreach($data as $key => $item) {
//
//            if($key === 15) {
//                break;
//            }
//
//            if(!isset($item['counterparty'])) {
//                continue;
//            }
//
//            $accountNumber = preg_replace('/[^0-9]/', '', $item['counterparty']);
//
//            if($accountNumber && strlen($accountNumber) > 15) {
//                $accounts[] = $accountNumber;
//            }
//        }
//
//        //Return error for statements for multiple accounts
//        if(count($accounts) > 1) {
//            throw new \Exception('Please upload statement for just one account');
//        }
//
//        return head($accounts);
//    }
}