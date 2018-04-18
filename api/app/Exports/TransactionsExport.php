<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Transaction;

class TransactionsExport implements FromCollection
{
    use Exportable;

    public function collection()
    {
        return Transaction::all();
    }
}