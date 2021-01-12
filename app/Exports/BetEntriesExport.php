<?php


namespace App\Exports;


use App\Models\BetTransaction;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class BetEntriesExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping,
    WithProperties,
    WithColumnFormatting,
    WithEvents
{

    use Exportable;

    private $fileName = 'Products.csv';
    private $request;
    private $collection;

    public function __construct(Request $request)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

//        Sheet::macro('freezeFirstRow', function (Sheet $sheet) {
//            $sheet->getDelegate()->;
//        });
        $this->request = $request;
    }

    public function collection()
    {
        if (!$game = Game::where('abbreviation', $this->request->get('game'))->first()) abort(400);
        $this->request->get('dates')[1] .= ' 23:59:59';
        $betTransactions = BetTransaction::whereBetween('created_at', $this->request->get('dates'))
            ->whereHas('bets', function ($query) use ($game) {
                $query->whereIn('draw_period_id', $this->request->get('draw_periods'))->where('game_id', $game->id);
            })->with('bets', 'user')->orderByDesc('created_at')->get();
        return $betTransactions->map(function ($item) {
            $item['sum'] = 0;
            $bets = [];
            $drawTime = '';
            foreach ($item['bets'] as $bet) {
                $item['sum'] += $bet->amount;
                $drawTime = $bet['drawPeriod']->draw_time;
                array_push($bets, $bet->combination);
            }

            $item['bets'] = implode(", ", $bets);
            $item['draw'] = Carbon::parse($drawTime)->format('g:i a');
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'TRANSACTION CODE',
            'AGENT NAME',
            'DRAW PERIOD',
            'COMBINATIONS',
            '₱ TOTAL',
            'CREATED AT',
            'UPDATED AT',
        ];
    }

    public function map($entries): array
    {
//        $sum = 0;
//        foreach ($entries->bets as $bet){
//            $sum+=$bet->amount;
//        }
        return [
            $entries->qr_code,
            $entries->user->name,
            $entries->draw,
            $entries->bets,
            $entries->sum,
            $entries->created_at,
            $entries->updated_at,
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => $this->request->user()->name,
            'lastModifiedBy' => $this->request->user()->name,
            'title' => 'Bet Transactions Data',
            'subject' => 'Bet Transactions Report',
            'keywords' => 'bet transactions,export,spreadsheet',
            'category' => 'Reports',
            'manager' => 'Small Town Lottery',
            'comments' => 'Okey keeu',
            'company' => 'InteRSYstem Digital Solutions',
        ];
    }


    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_TIME1,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
//                $event->sheet->freezeFirstRow();

                // last column as letter value (e.g., D)
                $last_column = Coordinate::stringFromColumnIndex(count($this->headings()));

                // calculate last row + 1 (total results + header rows + column headings row + new row)
                $last_row = count($this->collection()->all()) + 2 + 1 + 1;

                // set up a style array for cell formatting
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 15,
                        'bold' => true
                    )
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A1:%s1', $last_column));
                $event->sheet->mergeCells(sprintf('A2:%s2', $last_column));
                $event->sheet->mergeCells(sprintf('A%d:%s%d', $last_row, $last_column, $last_row));

                // assign cell values
                $event->sheet->setCellValue('A1', 'Bet Transactions Report');
                $event->sheet->setCellValue('A2', 'SMALL TOWN LOTTERY FROM DATE - '.implode(', ', array_values($this->request->get('dates'))));
                $event->sheet->setCellValue(sprintf('A%d', $last_row), 'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);

                $event->sheet->insertNewRowBefore(3, 2);
                $event->sheet->styleCells(
                    'A5:H5',
                    [
                        'font' => array(
                            'name' => 'Calibri',
                            'size' => 12,
                            'bold' => true
                        )
                    ]
                );
            },
        ];
    }
}
