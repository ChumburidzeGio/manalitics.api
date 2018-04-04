<?php
/**
 * Created by PhpStorm.
 * User: giorgi
 * Date: 3/31/18
 * Time: 12:10 PM
 */
namespace App\Parsers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction;

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

        return $this->normalizeAndFilter($cells);
    }

    /**
     * @param $file
     * @return array
     */
    public function import($file, $user)
    {
        $transactions = $this->parse($file);

        return $transactions->map(function($transaction) use($user) {

            $transaction['user_id'] = $user->id;

            $transaction = Transaction::updateOrCreate(
                array_only($transaction, ['bank', 'title', 'date', 'description', 'amount', 'user_id']),
                array_only($transaction, ['type', 'currency', 'is_expense', 'original'])
            );

            return $transaction;
        });
    }

    /**
     * @param $transaction
     * @return bool
     */
    public function validTransaction($transaction)
    {
        if(array_has($transaction, [
            'bank',
            'title',
            'date',
            'description',
            'type',
            'amount',
            'currency',
            'is_expense',
            'original'
        ])) {
            return true;
        }

        return false;
    }

    /**
     * @param $cells
     * @return mixed
     */
    public function normalizeAndFilter($cells)
    {
        $transactions = $cells->map(function ($item) {

            $normalized = $this->normalize($item);

            if(!$normalized) {
                return null;
            }

            $normalized = array_merge($normalized, [
                'bank' => $this->bankName,
                'amount' => floatval(str_replace(',', '.', $normalized['amount'])),
                'date' => $this->getDate($normalized['date'], $this->dateFormat),
            ]);

            if($this->validTransaction($normalized)) {

                return array_only($normalized, [
                    'bank', 'title', 'date', 'description', 'type',
                    'amount', 'currency', 'is_expense', 'original'
                ]);
            }

            return null;
        });

        return $transactions->filter();
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
        $carbon = $format ?
            Carbon::createFromFormat($format, $value) :
            Carbon::parse($value);

        return $carbon->format('Y/m/d');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getPosId($value)
    {
        return preg_replace('/\s+/', ' ', trim($value, '" '));
    }
}