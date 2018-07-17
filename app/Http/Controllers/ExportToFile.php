<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Http\Response;

class ExportToFile extends Controller
{
    private $index;

    private $sheet;

    /**
     * Export transactions into CSV
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $accounts = Account::where('user_id', $user->id)->get();

        $spreadsheet = new Spreadsheet();

        foreach($accounts as $i => $account)
        {
            $transactions = $account->transactions()->limit(5000)->get();

            $this->sheet = $spreadsheet->getSheet($i);

            $this->sheet->setTitle($account->name);

            $this->index = 1;

            $this->append('Title', 'Date', 'Description', 'Amount', 'Type', 'Currency', 'Is Expense');

            foreach ($transactions as $transaction)
            {
                $this->sheet
                    ->getStyle('C'.$this->index)
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    
                $this->append(
                    $transaction->title,
                    Date::PHPToExcel($transaction->date->format('Y-m-d')),
                    $transaction->description,
                    $transaction->amount,
                    $transaction->type,
                    $transaction->currency,
                    $transaction->is_expense
                );
            }
            
            $this->sheet->getColumnDimension('B')->setWidth(30);
            
            $this->sheet->getColumnDimension('D')->setWidth(40);
            
            $this->sheet->getColumnDimension('F')->setWidth(10);
        }

        $filename = 'transactions.xlsx';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=$filename");
        header("Content-Transfer-Encoding: binary ");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    private function append()
    {
        $values = func_get_args();

        $column = 'A';

        foreach ($values as $value)
        {
            $this->sheet->setCellValue($column.$this->index, $value);

            $column++;
        }

        $this->index++;
    }
}