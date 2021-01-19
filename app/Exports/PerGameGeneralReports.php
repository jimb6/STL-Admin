<?php


namespace App\Exports;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PerGameGeneralReports implements
    FromCollection,
    ShouldAutoSize,
    WithProperties,
    WithEvents,
    WithHeadings,
    WithColumnFormatting,
    WithCustomStartCell,
    WithStyles,
    WithMapping
{

    use Exportable;

    private $request;
    private $collection;
    private $perCluster;

    private $totalGross = 0;
    private $totalNet = 0;
    private $totalCommission = 0;
    private $totalHits = 0;
    private $totalHitsAmount = 0;
    private $totalCollectibles = 0;

    public function __construct(Request $request)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        Sheet::macro('cell', function (Sheet $sheet, string $cell, array $style) {
            $sheet->getDelegate()->getStyle($cell)->applyFromArray($style);
        });

        $this->request = $request;
    }

    public function collection()
    {
        if (!$this->request->hasValidSignature()) {
            abort(401);
        }
        $validated = $this->request->validate([
            'cluster_id' => 'required|array|min:1',
            'draw_period_id' => 'required|array|min:1',
            'game' => 'required',
            'dates' => 'required|array|max:2|min:2'
        ]);

        $day1 = Carbon::parse($validated['dates'][0])->startOfDay();
        $day2 = Carbon::parse($validated['dates'][1])->endOfDay();

        if (count($validated['cluster_id']) == 1) {
            $general_reports = DB::select("
                SELECT
                    u.name as agent_name,
                    dp.created_at as draw_date,
                    dp.draw_time as draw_period,
                    SUM(b.amount) as gross,
                    IF(MAX(c2.commission_rate), MAX(c2.commission_rate)*SUM(b.amount), 0) as commission,
                    IF(MAX(c2.commission_rate), SUM(b.amount) - (MAX(c2.commission_rate)*SUM(b.amount)), SUM(b.amount)) as net,
                    IF(COUNT(wb.id), SUM(b2.amount), 0) as hits,
                    IF(IF(COUNT(wb.id), SUM(b2.amount), 0), IF(COUNT(wb.id), SUM(b2.amount), 0) * MAX(gc.multiplier), 0) as amount_hits,
                    IF(MAX(c2.commission_rate), SUM(b.amount) - (MAX(c2.commission_rate)*SUM(b.amount)), SUM(b.amount)) - IF(IF(COUNT(wb.id), SUM(b2.amount), 0), IF(COUNT(wb.id), SUM(b2.amount), 0) * MAX(gc.multiplier), 0) as collectible
                from users u
                         left join `bet_transactions` as `bt` on u.id = bt.user_id AND bt.created_at BETWEEN '" . $day1 . "' AND '" . $day2 . "'
                            left outer join bets b on bt.id = b.bet_transaction_id
                            left join clusters c on c.id = u.cluster_id
                                left outer join commissions c2 on c.id = c2.cluster_id
                                    left join games g1 on g1.id = c2.game_id and g1.abbreviation = '" . $validated['game'] . "'
                            left join winning_bets wb on b.id = wb.bet_id
                                left join bets b2 on b2.id = wb.bet_id
                                    left join games g on g.id = b2.game_id and g.abbreviation = '" . $validated['game'] . "'
                            left join game_configurations gc on g.id = gc.game_id
                            inner join draw_periods dp on dp.id = b.draw_period_id and dp.id in (" . join(',', $validated['draw_period_id']) . ")
                where u.cluster_id in (" . join(',', $validated['cluster_id']) . ")
                group by u.name, b.draw_period_id;");

        } else {
            $general_reports = DB::select("
            SELECT
                    cluster_name as cluster_name,
                    draw_date as draw_date,
                    draw_time as draw_period,
                    sum(gross) as gross,
                    sum( IF(c.commission_rate, (commission_rate * gross), 0*gross) ) as commission,
                    sum( gross - IF(c.commission_rate, (commission_rate * gross), 0*gross) ) as net,
                    sum( hits ) as hits,
                    sum( hits * multiplier ) as amount_hits,
                    sum( gross - IF(c.commission_rate, (commission_rate * gross), 0*gross) - (hits * multiplier) ) as collectible
             FROM
                  (
                      SELECT u.cluster_id,
                             cl.name as cluster_name,
                             DATE(bt_draw_date)            as draw_date,
                             draw_time,
                             sum(amount)                   as gross,
                             IF(wb.bet_id, SUM(amount), 0) as hits,
                             GROUP_CONCAT(DISTINCT multiplier) as multiplier,
                             GROUP_CONCAT(DISTINCT game_id) as game_id
                      FROM users u,
                           clusters cl,
                           (SELECT bt.user_id    as bt_user_id,
                                   bt.created_at as bt_draw_date,
                                   dp.draw_time,
                                   b.id          as bet_id,
                                   amount,
                                   gc.multiplier,
                                   combination,
                                   g.id as game_id
                            FROM bets b, bet_transactions bt, draw_periods dp, games g, game_configurations gc
                            WHERE bt.created_at BETWEEN '" . $day1 . "' AND '" . $day2 . "'
                              AND gc.game_id = g.id
                              AND g.abbreviation = '" . $validated['game'] . "'
                              AND b.game_id = g.id
                              AND b.bet_transaction_id = bt.id
                              AND b.draw_period_id = dp.id
                              AND dp.id in (" . join(',', $validated['draw_period_id']) . ")
                           ) as bets_within_game_date
                      LEFT JOIN winning_bets wb on bets_within_game_date.bet_id = wb.bet_id
                      WHERE u.cluster_id = cl.id
                      AND cl.id in (" . join(',', $validated['cluster_id']) . ")
                      AND u.id = bets_within_game_date.bt_user_id
                      GROUP BY cluster_id, draw_time, wb.bet_id, DATE(bt_draw_date)
                  ) as computed_gross
             LEFT JOIN commissions c on c.cluster_id = computed_gross.cluster_id
             AND c.game_id = computed_gross.game_id

             GROUP BY computed_gross.cluster_id, draw_time, draw_date
             ORDER BY draw_date DESC, draw_time DESC;");
        }
        return collect($general_reports);
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
                $event->sheet->mergeCells(sprintf('A%d:%s%d', $last_row + 2, $last_column, $last_row + 2));

                // assign cell values
                $event->sheet->setCellValue('A1', " {$this->request->get('game')} General Report");
                $event->sheet->setCellValue('A2', 'SMALL TOWN LOTTERY FROM DATE - ' . implode(', ', array_values($this->request->get('dates'))));
                $event->sheet->setCellValue(sprintf('A%d', $last_row + 2), 'SMALL TOWN LOTTERY - COTABATO CITY');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
                $event->sheet->getStyle(sprintf('A%d', $last_row + 2))->applyFromArray($style_text_center);
                $event->sheet->insertNewRowBefore(3, 2);

                $event->sheet->styleCells(
                    'A5:I5',
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

                $event->sheet->insertNewRowBefore($last_row + 2, 1);
                $event->sheet->setCellValue(sprintf('A%d', $last_row + 2 + 1), "TOTAL");
                $event->sheet->setCellValue(sprintf('D%d', $last_row + 2 + 1), $this->totalGross);
                $event->sheet->setCellValue(sprintf('E%d', $last_row + 2 + 1), $this->totalCommission);
                $event->sheet->setCellValue(sprintf('F%d', $last_row + 2 + 1), $this->totalNet);
                $event->sheet->setCellValue(sprintf('G%d', $last_row + 2 + 1), $this->totalHits);
                $event->sheet->setCellValue(sprintf('H%d', $last_row + 2 + 1), $this->totalHitsAmount);
                $event->sheet->setCellValue(sprintf('I%d', $last_row + 2 + 1), $this->totalCollectibles);

                $event->sheet->styleCells(
                    sprintf('A%d:I%d', $last_row + 2 + 1,$last_row + 2 + 1),
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

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_TIME1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }


    public function headings(): array
    {

        return [
            "AGENT / CLUSTER",
            "DRAW DATE",
            "DRAW PERIOD",
            "GROSS",
            "COMMISSION",
            "NET",
            "HITS",
            "AMOUNT HITS",
            "COLLECTIBLE",
        ];

    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
    }

    public function fillBackground($hexColor)
    {
        return [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $hexColor,
                ]
            ],
        ];
    }

    public function map($row): array
    {
        $this->totalGross += $row->gross;
        $this->totalNet += $row->net;
        $this->totalCommission += $row->commission;
        $this->totalHits += $row->hits;
        $this->totalHitsAmount += $row->amount_hits;
        $this->totalCollectibles += $row->collectible;

        return [
            $row->cluster_name ? $row->cluster_name : $row->agent_name,
            $row->draw_date,
            $row->draw_period,
            $row->gross,
            $row->commission,
            $row->net,
            $row->hits,
            $row->amount_hits,
            $row->collectible,
        ];
    }
}
