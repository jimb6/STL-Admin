<?php


namespace App\Exports;


use App\Models\Bet;
use App\Models\Cluster;
use App\Models\Commission;
use App\Models\Game;
use App\Models\WinningBet;
use App\Models\WinningCombination;
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
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class GeneralReports implements
    FromCollection,
    ShouldAutoSize,
    WithProperties,
    WithEvents,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithCustomStartCell,
    WithStyles
{

    use Exportable;

    private $request;
    private $collection;

    public function __construct(Request $request)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        Sheet::macro('cell', function (Sheet $sheet, string $cell, array $style){
            $sheet->getDelegate()->getStyle($cell)->applyFromArray($style);
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

//              VARIABLES
                $dates =  implode(" to ", array_map(function ($date){
                    return date_format(date_create($date),"F j, Y");
                }, array_values( $this->request->get('dates') )) );

//              STYLES
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                ];
                $style_font_h1 = [
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 20,
                        'bold' => true
                    )
                ];
                $style_font_header = [
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 15,
                        'bold' => true
                    )
                ];
                $style_font_h2 = [
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => true
                    )
                ];
                $style_font_h6 = [
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                    )
                ];
                $style_text_white = [
                    'font' => array(
                        'color' => array(
                            'argb' => 'FFFFFFFF'
                        )
                    )
                ];
                $style_border = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],

                    ],
                ];

//              TABLE INDEX
                $columnStart = 'B';
                $columnEnd = 'H';
                $columnLength = 7;
                $rowStart = 6;
                $limit = 100;

                //GET INCLUDED CLUSTERS
                $clusters = Cluster::whereIn('id', $this->request->get('cluster_id'))->get()->map(function ($item){ return $item->name; })->toArray();


                // HEADER
                $event->sheet->mergeCells(sprintf('%s2:%s2', $columnStart, $columnEnd));
                $event->sheet->mergeCells(sprintf('%s3:%s3', $columnStart, $columnEnd));
                $event->sheet->mergeCells(sprintf('%s4:%s4', $columnStart, $columnEnd));
                $event->sheet->setCellValue("{$columnStart}2", "STL GENERAL REPORT");
                $event->sheet->setCellValue("{$columnStart}3", $dates );
                $event->sheet->setCellValue("{$columnStart}4", implode(', ', $clusters) );
                $event->sheet->styleCells("{$columnStart}2:{$columnStart}3", $style_text_center);
                $event->sheet->styleCells("{$columnStart}2", $style_font_h1);
                $event->sheet->styleCells("{$columnStart}3", $style_font_h6);

                // BACKGROUND WHITE and ROW 1 TEXT CENTER
                $event->sheet->styleCells( sprintf("A1:I{$limit}"), $this->fillBackground("FFFFFF"));
                $event->sheet->styleCells( sprintf("%s1:%s{$limit}", $columnStart, $columnStart), $style_text_center );

                // Loop each data (GAMES)
                foreach ($this->collection() as $data) {
                    $offset = 2;
                    $draws = count($data['drawPeriods']) + $offset;
                    $excel_gameName_range = sprintf('%s%d:C%d', $columnStart, $rowStart, $rowStart); // B6 - C6
                    $excel_gameName_cell = sprintf('%s%d', $columnStart, $rowStart); // B6
                    $excel_game_range = sprintf('D%d:%s%d', $rowStart, $columnEnd, $rowStart); // D6 - H6
                    $excel_header_range = sprintf('%s%d:%s%d', $columnStart, $rowStart, $columnEnd, $rowStart + 1); // B6 - H7
                    $excel_commission_cell = sprintf('D%d', $rowStart); // D6
                    $excel_multiplier_cell = sprintf('F%d', $rowStart); // F6

                    $event->sheet->mergeCells( $excel_gameName_range );
                    $event->sheet->setCellValue( $excel_gameName_cell, $data->abbreviation );
                    $event->sheet->styleCells( $excel_gameName_cell, $style_text_white);
                    $event->sheet->styleCells( $excel_gameName_cell, $this->fillBackground("FF7573") );
                    $event->sheet->styleCells( $excel_game_range, $this->fillBackground("FD9173") );
                    $event->sheet->styleCells( $excel_game_range, $style_text_white);
                    $event->sheet->styleCells( $excel_header_range, $style_font_h2 );
                    $event->sheet->styleCells( $excel_header_range, $style_text_center );
                    $event->sheet->styleCells( $excel_gameName_cell, $style_font_header );

                    // SUBHEADERS
                    $headers = ['DRAW', 'GROSS', 'COMMISSION', 'NET', 'HITS', 'COLLECTIBLES', 'KABIG/TAPAL'];
                    $mColumn = $columnStart;
                    foreach($headers as $headerTitle) {
                        $event->sheet->styleCells( sprintf('%s%d', $mColumn, $rowStart + 1), $this->fillBackground("2AC4C0"));
                        $event->sheet->styleCells( sprintf('%s%d', $mColumn, $rowStart + 1), $style_text_white);
                        $event->sheet->setCellValue(sprintf('%s%d', $mColumn++, $rowStart + 1), '  ' . $headerTitle . '  ');
                    }
                    // END OF - SUBHEADERS


                    $commission_rate = 0;
                    $commissions = Commission::where('game_id', $data->id)
                        ->whereIn('cluster_id', $this->request->get('cluster_id'))->get();
                    foreach ($commissions as $commission) {
                        $commission_rate += $commission->commission_rate; // total clusters rate
                    }
                    $event->sheet->setCellValue( $excel_commission_cell, $commission_rate*100 . '%' );

                    $lastRow = 0;
                    $totalGross = 0;
                    $totalCommissionRate = $commission_rate;
                    $totalNet = 0;
                    $totalHits = 0;
                    $totalCollectibles = 0;

                    for ($i = 1; $i <= count($data['drawPeriods']); $i++) {
                        $gross = Bet::whereBetween('created_at', $this->request->get('dates'))
                            ->where('draw_period_id', $data['drawPeriods'][$i - 1]->id)
                            ->where('game_id', $data->id)->get()->sum('amount');

                        $winCombis = WinningCombination::whereBetween('created_at', $this->request->get('dates'))
                            ->where('game_id', $data->id)->where('draw_period_id', $data['drawPeriods'][$i - 1]->id)->get();

                        $hits = 0;
                        foreach ($winCombis as $winCombi) {
                            $winningBets = WinningBet::where('winning_combination_id', $winCombi->id)->get();
                            foreach ($winningBets as $winningBet){
                                $bet = Bet::where('id', $winningBet->bet_id)->sum('amount');
                                $hits = $bet * $data['gameConfiguration']->multiplier;
                            }
                        }

                        $drawPeriod = Carbon::parse($data['drawPeriods'][$i - 1]->draw_time)->format('g:i A');
                        $commission = ($commission_rate * $gross);
                        $net = ($gross - ($gross * $commission_rate));
                        $collectibles = $net - $hits;

                        // CONTENTS
                        $contents = [$drawPeriod, $gross, $commission, $net, $hits, $collectibles, ''];
                        $mColumn = $columnStart;
                        foreach ($contents as $content) {
                            $event->sheet->setCellValue(sprintf('%s%d', $mColumn++, $rowStart + 1 + $i), $content);
                        }
                        $event->sheet->styleCells( sprintf('%s%d:%s%d', $columnStart, $rowStart + 1 + $i, $columnEnd, $rowStart + 1 + $i), $style_font_h6);

                        //Background Color (KABIG or TAPAL)
                        if( $collectibles != 0 ) {
                            $statusBg = ( $collectibles > 0 ) ? '4CAF50' : 'FF5252';
                            $event->sheet->styleCells(sprintf('%s%d', $columnEnd, $rowStart + 1 + $i), $this->fillBackground($statusBg));
                        }

                        $lastRow += $i;
                        $totalGross += $gross;
                        $totalNet += $net;
                        $totalHits += $hits;
                        $totalCollectibles += $collectibles;
                        $event->sheet->setCellValue( $excel_multiplier_cell, 'x' . $data['gameConfiguration']->multiplier );
                    }

                    if( $totalCollectibles != 0 ) {
                        $statusBg = ( $totalCollectibles > 0 ) ? '4CAF50' : 'FF5252';
                        $event->sheet->styleCells(sprintf('%s%d', $columnEnd, $rowStart + $draws), $this->fillBackground($statusBg));
                    }

                    $totals = ['TOTAL', $totalGross, ($totalCommissionRate * $totalGross), $totalNet, $totalHits, $totalCollectibles, ''];
                    $mColumn = $columnStart;
                    foreach ($totals as $total) {
                        $event->sheet->styleCells( sprintf('%s%d', $mColumn, $rowStart + $draws), $style_font_h2);
                        $event->sheet->setCellValue(sprintf('%s%d', $mColumn++, $rowStart + $draws), $total);
                    }
                    $event->sheet->styleCells( sprintf('%s%d:%s%d', $columnStart, $rowStart + $draws + 1, $columnEnd, $rowStart + $draws + 2), $this->fillBackground("FFFFFF") );

                    // BORDERS
                    $event->sheet->styleCells( $excel_game_range, $style_border);
                    $event->sheet->styleCells( sprintf('%s%d:%s%d', $columnStart, $rowStart, $columnEnd, $rowStart), $style_border);
                    $event->sheet->styleCells( sprintf('%s%d:%s%d', $columnEnd, $rowStart + 1, $columnEnd, $rowStart + $draws + 2), $style_border);
                    $event->sheet->styleCells( sprintf('%s%d:%s%d', $columnStart, $rowStart, $columnEnd, $rowStart + $draws + 2), $style_border);

                    $rowStart += $draws + 3; // Setting new row start
                }
                // END OF DATA
                for( $i = 0; $i < $columnLength; $i++ ){
                    $event->sheet->getColumnDimension( $columnStart++ )->setAutoSize(true);
                }
                $event->sheet->getColumnDimension(++$columnEnd)->setWidth(13.29);
                $event->sheet->setCellValue("A{$limit}", '           ');
                $event->sheet->styleCells( "A{$limit}", $style_text_white);
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

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
    }

    public function fillBackground($hexColor) {
        return  [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $hexColor,
                ]
            ],
        ];
    }
}
