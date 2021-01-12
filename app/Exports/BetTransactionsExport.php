<?php


namespace App\Exports;


use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;

class BetTransactionsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $fileName = 'Products.csv';
    private $writerType = Excel::CSV;
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Created at',
            'Updated at'
        ];
    }

}
