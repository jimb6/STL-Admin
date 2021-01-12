<?php


namespace App\Exports;


use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    private $fileName = 'Products.csv';

    /**
     * Optional Writer Type
     */
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
