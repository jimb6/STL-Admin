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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class CombinationReports implements
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
        $validated = $this->request->validate([
            'cluster_id' => 'required|array',
            'draw_period_id' => 'required|array',
            'game' => 'required',
            'dates' => 'required|array|max:2|min:2'
        ]);

        return $bets = DB::table('bets as b')
            ->select(DB::raw('b.combination as combination, SUM(b.amount) as amount'))
            ->whereIn('b.draw_period_id', $validated['draw_period_id'])
            ->leftJoin('bet_transactions as bt', 'bt.id', '=', 'b.bet_transaction_id')
            ->leftJoin('users as u', 'u.id','=', 'bt.user_id')
            ->whereIn('u.cluster_id', $validated['cluster_id'])
            ->leftJoin('draw_periods as dp', 'dp.id', '=', 'b.draw_period_id')
            ->leftJoin('games as g', 'g.id', '=', 'b.game_id')
            ->where('abbreviation', $validated['game'])
            ->groupBy('b.combination')
            ->get();
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

        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }


    public function headings(): array
    {
        return [
            'COMBINATIONS',
            'AMOUNT'
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function map($row): array
    {
        return [
            $row->combination,
            $row->amount
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
