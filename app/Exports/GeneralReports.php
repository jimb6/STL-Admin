<?php


namespace App\Exports;


use App\Models\BetTransaction;
use App\Models\Game;
use App\Models\WinningCombination;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class GeneralReports implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping,
    WithProperties,
    WithColumnFormatting,
    WithEvents
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
        if (! $this->request->hasValidSignature()) {
            abort(401);
        }

//        $validated = $request->validate([
//            'cluster_id' => 'required|array',
//            'draw_period_id' => 'required|array',
//            'game' => 'required',
//            'dates' => 'required|array|max:2|min:2'
//        ]);

        $game = Game::with('gameConfiguration')->where('abbreviation', $this->request->get('game'))->first();
        $this->request->get('dates')[1] .= ' 23:59:59';

        $bets = BetTransaction::whereBetween('created_at', $this->request->get('dates'))
            ->whereHas('user', function ($subQuery) {
                $subQuery->whereIn('cluster_id', $this->request->get('cluster_id'));
            })
            ->whereHas('bets', function ($query) use ($game) {
                $query->where('game_id', $game->id)
                    ->whereIn('draw_period_id', $this->request->get('draw_period_id'));
            })->with('user.cluster', 'bets.drawPeriod')->get();


        $winningCombinations = WinningCombination::where('game_id', $game->id)
            ->whereIn('draw_period_id', $this->request->get('draw_period_id'))
            ->whereBetween('created_at', $this->request->get('dates'))
            ->get();

        // SINGLE BET
        $bets = $bets->map(function ($item, $key) use ($winningCombinations, $game) {
            $sum = 0;
            $drawPeriod = '';
            $drawDate = '';
            $hits = 0;
            $item['bets_commission_rate'] = 0;

            // LOOPS IN EVERY BET
            foreach ($item['bets'] as $bet) {
                $sum += $bet['amount'];
                $drawPeriod = $bet['drawPeriod']->draw_time;
                $drawDate = $bet->created_at->format('m/d/Y');
                foreach ($winningCombinations as $winningCombination) {
                    if ($winningCombination->created_at->format('m/d/Y') === $drawDate && $bet['combination'] == $winningCombination->combination) {
                        $hits += $bet['amount'];
                    }
                }
            }

            // LOOPS IN EVERY COMMISSION
            foreach ($item['user']['cluster']['commissions'] as $commission) {
                if ($commission->game_id === $game->id) {
                    $item['bets_commission_rate'] = $commission->commission_rate;
                    break;
                }
            }

            $item['bets_amount'] = $sum;
            $item['bets_hit'] = $hits;
            $item['drawDate'] = $drawDate;
            $item['drawPeriod'] = $drawPeriod;
            $item['cluster'] = $item['user']['cluster']->name;
            return $item;
        });

        // SINGLE TRANSACTION
        $bets = $bets->groupBy('cluster')->transform(function ($item, $key) use ($game) {
            return $item->groupBy('drawDate')->map(function ($item2, $key) use ($game) {
                return $item2->groupBy('drawPeriod')->map(function ($item3, $key) use ($game) {
                    $gross = 0;
                    $commission_rate = 0;
                    $hits = 0;
                    foreach ($item3 as $transaction) {
                        $gross += $transaction['bets_amount'];
                        $hits += $transaction['bets_hit'];
                        $commission_rate = $transaction['bets_commission_rate'];
                    }
                    $item3['transaction_gross'] = $gross;
                    $item3['transaction_commission'] = $gross * $commission_rate;
                    $item3['transaction_net'] = $gross - ($gross * $commission_rate);
                    $item3['transaction_hits'] = $hits;
                    $item3['transaction_amount_hits'] = $hits * $game['gameConfiguration']->multiplier;
                    return $item3;
                });
            });
        });

       return $bets;
    }

    public function headings(): array
    {
        return [
            'CLUSTER NAME',
            'DRAW DATE',
//            'DRAW PERIOD',

//
//            'GROSS',
//            'COMMISSION',
//            'NET',
//            'HITS',
//            'AMOUNT HITS',
//            'COLLECTIBLE',
        ];
    }

    public function map($data): array
    {
//        cluster: cluster,
//                                    draw_date: drawDate,
//                                    draw_period: drawTime,
//                                    gross: data[cluster][drawDate][drawTime].transaction_gross,
//                                    commission: data[cluster][drawDate][drawTime].transaction_commission,
//                                    net: data[cluster][drawDate][drawTime].transaction_net,
//                                    hits: data[cluster][drawDate][drawTime].transaction_hits,
//                                    amount_hits: data[cluster][drawDate][drawTime].transaction_amount_hits,
//                                    payout: 0,
//                                    collectible: (data[cluster][drawDate][drawTime].transaction_net - data[cluster][drawDate][drawTime].transaction_amount_hits),
//                                    valid: true,
        return [
            $data->keys()[0],
            $data[$data->keys()[0]]->keys()[0],
//            $data[$data->keys()[0]][keys()[0]->keys()[0]],
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

        ];
    }


    public function registerEvents(): array
    {
        return [

            BeforeSheet::class => function(BeforeSheet $event){
// last column as letter value (e.g., D)
//                $last_column = Coordinate::stringFromColumnIndex(count($this->headings()));
//
//                // calculate last row + 1 (total results + header rows + column headings row + new row)
//                $last_row = count($this->collection()->all()) + 2 + 1 + 1;
//
//                // set up a style array for cell formatting
//                $style_text_center = [
//                    'alignment' => [
//                        'horizontal' => Alignment::HORIZONTAL_CENTER
//                    ],
//                    'font' => array(
//                        'name' => 'Calibri',
//                        'size' => 15,
//                        'bold' => true
//                    )
//                ];
//
//                // at row 1, insert 2 rows
//                $event->sheet->insertNewRowBefore(1, 2);
//
//                // merge cells for full-width
//                $event->sheet->mergeCells(sprintf('A1:%s1', $last_column));
//                $event->sheet->mergeCells(sprintf('A2:%s2', $last_column));
//                $event->sheet->mergeCells(sprintf('A%d:%s%d', $last_row, $last_column, $last_row));
//
//                // assign cell values
//                $event->sheet->setCellValue('A1', 'Bet Transactions Report');
//                $event->sheet->setCellValue('A2', 'SMALL TOWN LOTTERY FROM DATE - '.implode(', ', array_values($this->request->get('dates'))));
//                $event->sheet->setCellValue(sprintf('A%d', $last_row), 'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');
//
//                // assign cell styles
//                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
//                $event->sheet->getStyle(sprintf('A%d', $last_row))->applyFromArray($style_text_center);
//
//                $event->sheet->insertNewRowBefore(3, 2);
//                $event->sheet->styleCells(
//                    'A5:H5',
//                    [
//                        'font' => array(
//                            'name' => 'Calibri',
//                            'size' => 12,
//                            'bold' => true
//                        )
//                    ]
//                );
            },

            AfterSheet::class => function (AfterSheet $event) {
//                $event->sheet->freezeFirstRow();

            },
        ];
    }
}
