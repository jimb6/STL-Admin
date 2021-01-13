<?php


namespace App\Exports;


use App\Models\Bet;
use App\Models\Commission;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class GeneralReports implements
    FromCollection,
    ShouldAutoSize,
    WithProperties,
    WithEvents,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomStartCell
{

    use Exportable;

    private $request;
    private $collection;

    public function __construct(Request $request)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        $this->request = $request;
    }

    public function collection()
    {
        if (!$this->request->hasValidSignature()) {
            abort(401);
        }
        $data = $this->request->all();
        return Game::with('drawPeriods')->get();
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


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
//                $event->sheet->freezeFirstRow();

//                 set up a style array for cell formatting
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

                // last column as letter value (e.g., D)
                $last_column = 'F';

                // calculate last row + 1 (total results + header rows + column headings row + new row)
                $last_row = count($this->collection()->all()) + 2 + 1 + 1;

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A1:%s1', $last_column));
                $event->sheet->mergeCells(sprintf('A2:%s2', $last_column));
//                $event->sheet->mergeCells(sprintf('A%d:%s%d', $last_row + 2, $last_column, $last_row + 2));

                // assign cell values
                $event->sheet->setCellValue('A1', 'GAME GROSS GENERAL REPORT');
                $event->sheet->setCellValue('A2', 'SMALL TOWN LOTTERY FROM DATE - ' . implode(', ', array_values($this->request->get('dates'))));
//                $event->sheet->setCellValue(sprintf('A%d', $last_row+2), 'SMALL TOWN LOTTERY - COTABATO CITY');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
//                $event->sheet->getStyle(sprintf('A%d', $last_row + 2))->applyFromArray($style_text_center);
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

                $event->sheet->styleCells(
                    'A1:A2',
                    [
                        'color' => '#292C3F'
                    ]
                );

                $startingRow = 5;
                $startingColumn = 1;

                foreach ($this->collection() as $data) {
                    $event->sheet->mergeCells(sprintf('A%d:B%d', $startingRow, $startingRow));
//                    $event->sheet->mergeCells(sprintf('A%s:A%s', $startingRow+3, $startingRow+7));

                    $offset = 4;
                    $draws = count($data['drawPeriods']) + $offset;
                    $event->sheet->setCellValue(sprintf('A%d', $startingRow), $data->abbreviation);
                    $event->sheet->styleCells(
                        sprintf('A%d:B%d', $startingRow, $startingRow),
                        [
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => true,
                            ),
                            'color' => '#FB8C00'
                        ]
                    );
                    $event->sheet->styleCells(
                        sprintf('A%d:G%d', $startingRow + 1, $startingRow + 1),
                        [
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => true
                            )
                        ]
                    );

                    $event->sheet->setCellValue(sprintf('A%d', $startingRow + 1), 'DRAW');
                    $event->sheet->setCellValue(sprintf('B%d', $startingRow + 1), 'GROSS');
                    $event->sheet->setCellValue(sprintf('C%d', $startingRow + 1), 'COMMISSION');
                    $event->sheet->setCellValue(sprintf('D%d', $startingRow + 1), 'NET');
                    $event->sheet->setCellValue(sprintf('E%d', $startingRow + 1), 'HITS');
                    $event->sheet->setCellValue(sprintf('F%d', $startingRow + 1), 'COLLECTIBLES');
                    $event->sheet->setCellValue(sprintf('G%d', $startingRow + 1), 'KABIG/TAPAL');

                    $sum = 0;
                    $commissions = Commission::where('game_id', $data->id)->get();
                    foreach ($commissions as $commission) {
                        $sum += $commission->commission_rate;
                    }

                    for ($i = 1; $i <= count($data['drawPeriods']); $i++) {
                        $event->sheet->setCellValue(sprintf('A%d', $startingRow + 1 + $i), Carbon::parse($data['drawPeriods'][$i - 1]->draw_time)->format('g:i a'));

                        $gross = Bet::whereBetween('created_at', $this->request->get('dates'))
                            ->where('draw_period_id', $data['drawPeriods'][$i - 1]->id)
                            ->where('game_id', $data->id)->get()->sum('amount');

                        $event->sheet->setCellValue(sprintf('C%d', $startingRow + 1 + $i), $sum);
                        $event->sheet->setCellValue(sprintf('B%d', $startingRow + 1 + $i), $gross);
                        $event->sheet->setCellValue(sprintf('D%d', $startingRow + 1 + $i), ($gross - ($gross * $sum)));
                    }

                    $startingRow += $draws;
                }


            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_TIME1,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }


    public function headings(): array
    {
        return [];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function map($row): array
    {
        return [

        ];
    }
}
