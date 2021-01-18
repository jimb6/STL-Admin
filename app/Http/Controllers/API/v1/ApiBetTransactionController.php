<?php

namespace App\Http\Controllers\API\v1;

use App\Events\DashboardEvent;
use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\ControlCombination;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\GameConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ApiBetTransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        return response(['betTransactions'], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-bet-transactions', BetTransaction::class);
        //Validate the user inputs
        $validated = $request->validate([
            'bets.*' => 'required',
            'bets.*.amount' => ['required', 'numeric', function ($attrib, $value, $fail) {
                if ($value % 5 !== 0) {
                    $fail('Bet\'s amount must be divisible by 5');
                }
            }],
            'bets' => 'required|array|min:1|max:10',
        ]);


//        VARIABLES
        $today = Carbon::today(config('app.timezone'));
        $now = Carbon::now(config('app.timezone'))->toTimeString();
        $bets = $validated['bets'];
        $game = $bets[0]['game_id'];
        $gameAbbreviation = $bets[0]['game_abbreviation'];
        $agentId = $request->user('sanctum')->id;


//        DB QUERY BUILDER
//        IDENTIFY ALL SOLD OUT NUMBERS
//        $closeNumbers = DB::table('close_numbers')
//            ->whereIn('combination', array_column($bets, 'combination'))
//            ->whereDate('created_at', $today)
//            ->where('game_id', $bets[0]['game_id'])
//            ->get();
//
////        IF BETS SUBMITTED IS SOLD OUT RETURN 406
//        if ($closeNumbers->isNotEmpty()) return response(['message' => 'Some numbers are closed. ' . $closeNumbers], 406);
//
////        IF BETS DOESN'T HAVE A VALID DRAW PERIOD RETURN 406
//        if (!$draw = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($bets) {
//            $query->where('id', $bets[0]['game_id']);
//        })->first()) return response(['message' => 'Cannot process the bets. The game is in progress.' . $closeNumbers], 406);
//
////        GET THE GAME CONFIGURATION OF SUBMITTED BETS
//        $game = Game::where('id', $bets[0]['game_id'])->with('gameConfiguration')->get()
//            ->map(function ($row) {
//                return $row->getRelation('gameConfiguration');
//            });
//
//        $tempVals = [];
//        foreach ($bets as $bet) {
////            GET THE CURRENT SUM AMOUNT OF SPECIFIC BET
//            $sumOfBet = DB::table('bets')
//                ->select(DB::raw('combination, SUM(amount) as amount'))
//                ->whereDate('created_at', $today)
//                ->where('draw_period_id', $draw->id)
//                ->where('game_id', $bet['game_id'])
//                ->where('combination', $bet['combination'])
//                ->groupBy('combination')
//                ->sum('amount');
//
////            if ($sumOfBet) return response($sumOfBet, 200);
//
//
////            EXPLODE ALL COMBINATIONS e.g (10-20-30) = [10, 20, 30]
//            $combinations = explode('-', $bet['combination']);
//            if ((count(array_unique($combinations)) != count($combinations)) && !$game[0]->has_repetition) abort(406);
//            foreach ($combinations as $combination) {
//                if (strlen($combination) != $game[0]->digit_per_field_set) return response(['message' => 'Invalid combination.'], 406);
//                if (intval($combination) > $game[0]->max_per_field_set) return response(['message' => 'Invalid combination.'], 406);
//                if (intval($combination) < $game[0]->min_per_field_set) return response(['message' => 'Invalid combination.'], 406);
//            }
//
//            //Check the maximum amount per bet
//            if ($bet['amount'] > $game[0]->max_per_bet)
//                return response(['message' => 'Amount exceeds the maximum. might be less than or equal ' . $game[0]->max_per_bet], 406);
//            //Check the minimum amount per bet
//            if ($bet['amount'] < $game[0]->min_per_bet)
//                return response(['message' => 'Invalid amount. Must be greater than or equal ' . $game[0]->min_per_bet], 406);
//            //Check the total amount of the bet
//            if ($sumOfBet != null && ($sumOfBet + $bet['amount']) > $game[0]->max_sum_bet)
//                return response(['message' => 'Amount exceeds. Available amount ' . ($sumOfBet - $game[0]->max_sum_bet)], 406);
//
//            //Get the control combinations
//            $control = ControlCombination::where('game_id', $game[0]->game_id)->where('combination', $bet['combination'])->sum('max_amount');
//
//            //check the control combinations
//            if ($sumOfBet != null && $sumOfBet + $bet['amount'] >= $control)
//                return response(['message' => 'Amount exceeds in combination '.$bet['combination'].'. Available amount ' . ($sumOfBet - $control)], 406);
//
//            array_push($tempVals, array_merge($bet, ['draw_period_id' => $draw->id]));
//        }

        //Check Close number

        $draw = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($game) {
            $query->where('id', $game);
        })->first();

        //Check if bet has a valid draw_period  (Pwedi siya dili isulod sa loop)
        if (!$draw) return response(['message' => 'Cannot processed the bets. The game is in progress.'], 406);

        $tempVals = [];
        foreach ($bets as $bet) {

            //Get the Sum of bets amount from the same submitted Bets
            $permutedValues = $this->permutate(explode('-', $bet['combination']));
            $betSum = 0;
            $serializedPerVals = [];
            foreach ($permutedValues as $permutedValue) {
                $permutedValue = join('-', $permutedValue);
                array_push($serializedPerVals, $permutedValue);
                $betSum += Bet::currentDraw()->where('game_id', $bet['game_id'])->where('combination', 'like', "%{$permutedValue}%")->sum('amount');
            }


            //Get Game Configuration to compare the maximum and minimum bets amount
            $gameConfig = GameConfiguration::where('game_id', $bet['game_id'])->first();

            // Comparing....
            // COMBI >>>>       01-50
            // Pares range >>>  1-38
            // COMBI >>>>       123-R

            $combinations = $permutedValues[0];
            if ((count(array_unique($combinations)) != count($combinations)) && !$gameConfig->has_repetition)
                return response(['message' => "Combination, invalid. Repetition is not acceptable."], 406);
            if ((count($combinations) != $gameConfig->field_set))
                return response(['message' => "A transaction is invalid. The minimum combo is {$gameConfig->field_set}."], 406);;
            foreach ($combinations as $combination) {
                if (strlen($combination) != $gameConfig->digit_per_field_set)
                    return response(['message' => "Invalid combination."], 406);
                if (intval($combination) > $gameConfig->max_per_field_set)
                    return response(['message' => "The maximum number should be {$gameConfig->max_per_field_set}."], 406);
                if (intval($combination) < $gameConfig->min_per_field_set)
                    return response(['message' => "The minimum number should be {$gameConfig->min_per_field_set}."], 406);
            }

            //Check the maximum amount per bet
            if ($bet['amount'] > $gameConfig->max_per_bet)
                return response(['message' => 'Amount exceeds the maximum. might be less than or equal ' . $gameConfig->max_per_bet], 406);
            //Check the minimum amount per bet
            if ($bet['amount'] < $gameConfig->min_per_bet)
                return response(['message' => 'Invalid amount. Must be greater than or equal ' . $gameConfig->min_per_bet], 406);
            //Check the total amount of the bet
            if ($betSum && ($betSum + $bet['amount']) > $gameConfig->max_sum_bet)
                return response(['message' => 'Amount exceeds on bet ' . $bet['combination'] . '. Available amount ' . ($betSum - $gameConfig->max_sum_bet)], 406);

            //Get the control combinations
            $control = ControlCombination::where('game_id', $gameConfig->game_id)->where('combination', $bet['combination'])->sum('max_amount');

            //check the control combinations
            if ($betSum && $control && $betSum + $bet['amount'] > $control)
                return response(['message' => 'Amount exceeds on bet ' . $bet['combination'] . '. Available amount ' . ($betSum - $control)], 406);
            array_push($tempVals, array_merge($bet, ['draw_period_id' => $draw['id']]));
        }

        $transaction = null;
        try {
            $transaction = BetTransaction::create([
                'user_id' => $agentId
            ]);
        } catch (\Exception $ex) {
            return response(['message' => $ex], 400);
        }

        foreach ($tempVals as $val) {
            Bet::create([
                'combination' => $val['combination'],
                'amount' => $val['amount'],
                'is_rumbled' => $val['is_rumbled'],
                'bet_transaction_id' => $transaction->id,
                'game_id' => $val['game_id'],
                'draw_period_id' => $val['draw_period_id'],
            ]);
        }

        broadcast(new NewBetTransactionAdded($gameAbbreviation));
        broadcast(new DashboardEvent($transaction));

        $draw = DrawPeriod::find($tempVals[0]['draw_period_id']);
        return response(['transaction' => $transaction, 'draw_period' => $draw], 202);
    }

    public function show(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('view-bet-transactions', BetTransaction::class);
        return response([], 204);
    }

    public function showByGame(Request $request, $game)
    {
        $this->authorize('view-bet-transactions', BetTransaction::class);
        $game = Game::where('abbreviation', $game)->first();
        $drawPeriod = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($game) {
            $query->where('id', $game->id);
        })->first();

        $betTransactions = [];
        if ($drawPeriod) {
            $betTransactions = DB::table('bets as b')
                ->select(DB::raw('COUNT(*) as count, SUM(b.amount) as total, MAX(config.max_sum_bet) as max, b.combination, MAX(cc.max_amount) as control, IF(cn.combination, true, false) as closed, dp.draw_time'))
                ->whereDate('b.created_at', Carbon::today()->toDateString())
                ->where('b.game_id', $game->id)
                ->where('b.draw_period_id', $drawPeriod->id)
                ->join('game_configurations as config', function ($query) {
                    $query->on('b.game_id', '=', 'config.game_id');
                })
                ->leftJoin('control_combinations as cc', 'cc.combination', '=', 'b.combination')
                ->leftJoin('close_numbers as cn', 'cn.combination', '=', 'b.combination')
                ->leftJoin('draw_periods as dp', 'dp.id', '=', 'b.draw_period_id')
                ->groupBy('b.combination', 'b.draw_period_id')
                ->orderByDesc('total')->get();
        }

        return response(['bets' => $betTransactions, 'draw' => $drawPeriod], 200);

    }

    public function edit(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update-bet-transactions', $betTransaction);
        return response(['betTransactions' => $betTransaction], 200);
    }

    public function update(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update-bet-transactions', $betTransaction);
        $validated = $request->validated();
        $betTransaction->update($validated);
        return response([$betTransaction], 202);
    }

    public function destroy(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('delete-bet-transactions', $betTransaction);
        $betTransaction->delete();
        return response([], 204);
    }

    public function showEntriesBasedOnDateRange(Request $request)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $validated = $request->validate([
            'dates' => 'required|array|min:2|max:2',
            'draw_periods' => 'required|array',
            'game' => 'required',
        ]);

        if (!$game = Game::where('abbreviation', $validated['game'])->first()) abort(400);

        $betTransactions = DB::table('bet_transactions as bt')
            ->select(DB::raw('SUM(b.amount) as total, GROUP_CONCAT(DISTINCT concat(b.combination,\'=\',b.amount)) as combinations,
            bt.id, bt.qr_code, bt.is_void, bt.printable, bt.created_at, bt.updated_at, u.name, dp.draw_time, bt.deleted_at, bt.is_void'))
            ->whereBetween('bt.created_at',
                [Carbon::parse($validated['dates'][0])->startOfDay(),
                    Carbon::parse($validated['dates'][1])->endOfDay()])
            ->whereExists(function ($query) use ($game, $validated) {
                $query->select(DB::raw('amount, combination'))
                    ->from('bets')
                    ->whereColumn('bets.bet_transaction_id', 'bt.id')
                    ->where('bets.game_id', $game->id)
                    ->whereIn('bets.draw_period_id', $validated['draw_periods']);
            })
            ->join('bets as b', function ($join) {
                $join->on('bt.id', '=', 'b.bet_transaction_id')
                    ->join('draw_periods as dp', 'b.draw_period_id', '=', 'dp.id');
            })
            ->join('users as u', 'bt.user_id', '=', 'u.id')
            ->orderByDesc('bt.created_at')
            ->where('bt.is_void', '=', false)
            ->groupBy('b.bet_transaction_id', 'u.name', 'b.draw_period_id')
            ->get();

//        $betTransactions = BetTransaction::whereBetween('created_at', [Carbon::parse($validated['dates'][0])->startOfDay(), Carbon::parse($validated['dates'][1])->endOfDay()])
//            ->whereHas('bets', function ($query) use ($game, $validated) {
//                $query->whereIn('draw_period_id', $validated['draw_periods'])->where('game_id', $game->id);
//            })->with('bets', 'user')->orderByDesc('created_at')->get();

        $reportUrl = URL::temporarySignedRoute('reports.bet.entries.generate',
            now()->addMinutes(30),
            ['dates' => $validated['dates'], 'draw_periods' => $validated['draw_periods'], 'game' => $validated['game']]);
        return response(['transactions' => $betTransactions, 'report_url' => $reportUrl], 200);
    }

    public function getGeneralBetsReport(Request $request)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $validated = $request->validate([
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
                    dp.created_at as draw_date,
                    c.name as cluster_name,
                    u.name as agent_name,
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
            SELECT computed_gross.cluster_id as cluster_id,
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

        $reportUrl = URL::temporarySignedRoute('reports.bet.general.generate',
            now()->addMinutes(30),
            ['cluster_id' => $validated['cluster_id'], 'draw_period_id' => $validated['draw_period_id'],
                'game' => $validated['game'], 'dates' => $validated['dates']]);

        return response(['generalReports' => $general_reports, 'report_url' => $reportUrl], 200);
    }

    public function getAgentTransactions(Request $request, $date)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $betTransactions = BetTransaction::with('bets')->whereDate('created_at', $date)
            ->orderByDesc('created_at')
            ->get();

        return response(['betTransactions' => $betTransactions], 200);
    }

    public function updatePrintableStatus(Request $request, $id)
    {
        $this->authorize('update-bet-transactions', BetTransaction::class);
        $validated = $request->validate(['printable' => 'required']);
        $betTransactions = BetTransaction::find($id)->whereDate('created_at', Carbon::today())->update($validated);
        if (!$betTransactions) abort(406);
        return response([], 204);
    }

    private function swap($res, $a, $b)
    {
        $tmp = $res[$a];
        $res[$a] = $res[$b];
        $res[$b] = $tmp;
        return $res;
    }

    private function permutate($list, $index = 0)
    {
        if (count($list) - 1 <= $index) return [array_splice($list, 0)];
        $res = [];
        for ($i = $index; $i < count($list); $i++) $res = array_merge($res, $this->permutate($this->swap($list, $i, $index), $index + 1));
        return array_map("unserialize", array_unique(array_map("serialize", $res)));
    }

    private function validateDrawPeriod()
    {

    }

    // Reports
    public function getReports(Request $request) {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $validated = $request->validate([
            'report_type' => 'required',
            'cluster_id' => 'required|array|min:1',
            'dates' => 'required|array|max:2|min:2'
        ]);


        $cluster_ids = join(',', $validated['cluster_id']);
        $day1 = Carbon::parse($validated['dates'][0])->startOfDay();
        $day2 = Carbon::parse($validated['dates'][1])->endOfDay();
        $reports = '';
        $game_abbreviations = '';
        $reportUrl = '';

        switch ($validated['report_type']) {
            case 'byCluster':
                $reports = DB::select("
                    SELECT cl2.name as cluster_name,
                           GROUP_CONCAT(DISTINCT g2.abbreviation) as game_names,
                           SUM(IF(gross, gross, 0)) as gross,
                           SUM(IF(commission, commission, 0)) as commission,
                           SUM(IF(net, net, 0)) as net,
                           SUM(IF(hits, hits, 0)) as hits,
                           SUM(IF(amount_hits, amount_hits, 0)) as amount_hits,
                           SUM(IF(collectible, collectible, 0)) as collectible
                    FROM draw_period_game dpg LEFT OUTER JOIN (
                        SELECT GROUP_CONCAT(DISTINCT bt_bets.game_id) as game_id,
                               GROUP_CONCAT(DISTINCT bt_bets.dp_id) as dp_id,
                               cl.name as cl_name,
                               cl.id as cl_id,
                               abbreviation                                                                                                               as game_name,
                               draw_time                                                                                                                  as draw_period,
                               SUM(amount)                                                                                                                as gross,
                               IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                             as commission,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                 as net,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount, 0))                                                                             as hits,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))                                                        as amount_hits,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0) - SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))  as collectible
                        FROM users u
                             INNER JOIN (
                                SELECT bt.user_id, g.abbreviation, dp.draw_time, b.amount, g.id as game_id, b.id as bet_id, gc.multiplier, dp.id as dp_id
                                FROM bet_transactions bt
                                    LEFT JOIN bets b on bt.id = b.bet_transaction_id AND bt.created_at BETWEEN '{$day1}' AND '{$day2}'
                                    LEFT JOIN games g on b.game_id = g.id
                                    LEFT JOIN game_configurations gc on gc.game_id = g.id
                                    LEFT JOIN draw_periods dp on b.draw_period_id = dp.id
                                ORDER BY b.id
                             ) as bt_bets on bt_bets.user_id = u.id
                            INNER JOIN clusters cl on cl.id = u.cluster_id AND cl.id in ({$cluster_ids})
                            LEFT JOIN commissions c on cl.id = c.cluster_id AND c.game_id = bt_bets.game_id
                            LEFT JOIN winning_bets wb on wb.bet_id = bt_bets.bet_id
                        GROUP BY abbreviation, draw_time, cl.id
                      ) as reports on reports.game_id =  dpg.game_id AND reports.dp_id = dpg.draw_period_id
                    LEFT JOIN games g2 on dpg.game_id = g2.id
                    LEFT JOIN draw_periods dp2 on dpg.draw_period_id = dp2.id
                    RIGHT OUTER JOIN clusters cl2 on cl2.id = reports.cl_id
                    GROUP BY cl2.id
                ");
                break;
            case 'byAgent':
                $games_query = DB::select("
                    SELECT
                      GROUP_CONCAT(DISTINCT CONCAT( 'SUM(case when bet_transaction.abbreviation=''', g.abbreviation , ''' THEN bet_transaction.amount END) AS `', g.abbreviation , '`')) as 'game_query',
                      GROUP_CONCAT(DISTINCT g.abbreviation) as game_abbreviation
                    FROM games g
                ")[0];
                $game_abbreviations = $games_query->game_abbreviation;
                $reports = DB::select("
                    SELECT u.name as agent_name, GROUP_CONCAT(DISTINCT d.device_code) as device_code, {$games_query->game_query} , sum(bet_transaction.amount) as total_gross
                    FROM users u
                             LEFT OUTER JOIN devices d on u.id = d.user_id
                             LEFT OUTER JOIN (
                                                SELECT bt.user_id, amount, g.abbreviation
                                                FROM bet_transactions bt, bets b, games g
                                                WHERE bt.id = b.bet_transaction_id
                                                AND g.id = b.game_id
                                                AND g.id in (SELECT g2.id FROM games g2)
                                                AND bt.created_at BETWEEN '{$day1}' AND '{$day2}'
                                             ) as bet_transaction on u.id = bet_transaction.user_id
                             INNER JOIN clusters c on u.cluster_id = c.id AND c.id in ({$cluster_ids})
                    GROUP BY u.id;");
                break;
            case 'byDrawPeriod':
                $reports = DB::select("
                    SELECT dp2.draw_time as draw_period,
                           GROUP_CONCAT(DISTINCT g2.abbreviation) as game_name,
                           SUM(IF(gross, gross, 0)) as gross,
                           SUM(IF(commission, commission, 0)) as commission,
                           SUM(IF(net, net, 0)) as net,
                           SUM(IF(hits, hits, 0)) as hits,
                           SUM(IF(amount_hits, amount_hits, 0)) as amount_hits,
                           SUM(IF(collectible, collectible, 0)) as collectible
                    FROM draw_period_game dpg LEFT OUTER JOIN (
                        SELECT GROUP_CONCAT(DISTINCT bt_bets.game_id) as game_id,
                               GROUP_CONCAT(DISTINCT bt_bets.dp_id) as dp_id,
                               abbreviation                                                                                                               as game_name,
                               draw_time                                                                                                                  as draw_period,
                               SUM(amount)                                                                                                                as gross,
                               IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                             as commission,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                 as net,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount, 0))                                                                             as hits,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))                                                        as amount_hits,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0) - SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))  as collectible
                        FROM users u
                             INNER JOIN (
                                SELECT bt.user_id, g.abbreviation, dp.draw_time, b.amount, g.id as game_id, b.id as bet_id, gc.multiplier, dp.id as dp_id
                                FROM bet_transactions bt
                                    LEFT JOIN bets b on bt.id = b.bet_transaction_id AND bt.created_at BETWEEN '{$day1}' AND '{$day2}'
                                    LEFT JOIN games g on b.game_id = g.id
                                    LEFT JOIN game_configurations gc on gc.game_id = g.id
                                    LEFT JOIN draw_periods dp on b.draw_period_id = dp.id
                                ORDER BY b.id
                             ) as bt_bets on bt_bets.user_id = u.id
                            INNER JOIN clusters cl on cl.id = u.cluster_id AND cl.id in ({$cluster_ids})
                            LEFT JOIN commissions c on cl.id = c.cluster_id AND c.game_id = bt_bets.game_id
                            LEFT JOIN winning_bets wb on wb.bet_id = bt_bets.bet_id
                        GROUP BY abbreviation, draw_time
                      ) as reports on reports.game_id =  dpg.game_id AND reports.dp_id = dpg.draw_period_id
                    LEFT JOIN games g2 on dpg.game_id = g2.id
                    LEFT JOIN draw_periods dp2 on dpg.draw_period_id = dp2.id
                    GROUP BY dp2.id
                ");
                break;
            case 'byGame':
                $reports = DB::select("
                    SELECT g2.abbreviation as game_name,
                           GROUP_CONCAT(DISTINCT dp2.draw_time) as draw_period,
                           SUM(IF(gross, gross, 0)) as gross,
                           SUM(IF(commission, commission, 0)) as commission,
                           SUM(IF(net, net, 0)) as net,
                           SUM(IF(hits, hits, 0)) as hits,
                           SUM(IF(amount_hits, amount_hits, 0)) as amount_hits,
                           SUM(IF(collectible, collectible, 0)) as collectible
                    FROM draw_period_game dpg LEFT OUTER JOIN (
                        SELECT GROUP_CONCAT(DISTINCT bt_bets.game_id) as game_id,
                               GROUP_CONCAT(DISTINCT bt_bets.dp_id) as dp_id,
                               abbreviation                                                                                                               as game_name,
                               draw_time                                                                                                                  as draw_period,
                               SUM(amount)                                                                                                                as gross,
                               IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                             as commission,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                 as net,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount, 0))                                                                             as hits,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))                                                        as amount_hits,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0) - SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))  as collectible
                        FROM users u
                             INNER JOIN (
                                SELECT bt.user_id, g.abbreviation, dp.draw_time, b.amount, g.id as game_id, b.id as bet_id, gc.multiplier, dp.id as dp_id
                                FROM bet_transactions bt
                                    LEFT JOIN bets b on bt.id = b.bet_transaction_id AND bt.created_at BETWEEN '{$day1}' AND '{$day2}'
                                    LEFT JOIN games g on b.game_id = g.id
                                    LEFT JOIN game_configurations gc on gc.game_id = g.id
                                    LEFT JOIN draw_periods dp on b.draw_period_id = dp.id
                                ORDER BY b.id
                             ) as bt_bets on bt_bets.user_id = u.id
                            INNER JOIN clusters cl on cl.id = u.cluster_id AND cl.id in ({$cluster_ids})
                            LEFT JOIN commissions c on cl.id = c.cluster_id AND c.game_id = bt_bets.game_id
                            LEFT JOIN winning_bets wb on wb.bet_id = bt_bets.bet_id
                        GROUP BY abbreviation, draw_time
                      ) as reports on reports.game_id =  dpg.game_id AND reports.dp_id = dpg.draw_period_id
                    LEFT JOIN games g2 on dpg.game_id = g2.id
                    LEFT JOIN draw_periods dp2 on dpg.draw_period_id = dp2.id
                    GROUP BY g2.id
                ");
                break;
            case 'byDrawPeriodGame':
                $reports = DB::select("
                    SELECT dp2.draw_time as draw_period,
                           g2.abbreviation as game_name,
                           IF(gross, gross, 0) as gross,
                           IF(commission, commission, 0) as commission,
                           IF(net, net, 0) as net,
                           IF(hits, hits, 0) as hits,
                           IF(amount_hits, amount_hits, 0) as amount_hits,
                           IF(collectible, collectible, 0) as collectible
                    FROM draw_period_game dpg LEFT OUTER JOIN (
                        SELECT GROUP_CONCAT(DISTINCT bt_bets.game_id) as game_id,
                               GROUP_CONCAT(DISTINCT bt_bets.dp_id) as dp_id,
                               abbreviation                                                                                                               as game_name,
                               draw_time                                                                                                                  as draw_period,
                               SUM(amount)                                                                                                                as gross,
                               IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                             as commission,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0)                                                 as net,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount, 0))                                                                             as hits,
                               SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))                                                        as amount_hits,
                               SUM(amount)-IF(COUNT(c.commission_rate), SUM(c.commission_rate*amount), 0) - SUM(IF(bt_bets.bet_id = wb.bet_id, amount * bt_bets.multiplier, 0))  as collectible
                        FROM users u
                             INNER JOIN (
                                SELECT bt.user_id, g.abbreviation, dp.draw_time, b.amount, g.id as game_id, b.id as bet_id, gc.multiplier, dp.id as dp_id
                                FROM bet_transactions bt
                                    LEFT JOIN bets b on bt.id = b.bet_transaction_id AND bt.created_at BETWEEN '{$day1}' AND '{$day2}'
                                    LEFT JOIN games g on b.game_id = g.id
                                    LEFT JOIN game_configurations gc on gc.game_id = g.id
                                    LEFT JOIN draw_periods dp on b.draw_period_id = dp.id
                                ORDER BY b.id
                             ) as bt_bets on bt_bets.user_id = u.id
                            INNER JOIN clusters cl on cl.id = u.cluster_id AND cl.id in ({$cluster_ids})
                            LEFT JOIN commissions c on cl.id = c.cluster_id AND c.game_id = bt_bets.game_id
                            LEFT JOIN winning_bets wb on wb.bet_id = bt_bets.bet_id
                        GROUP BY abbreviation, draw_time
                      ) as reports on reports.game_id =  dpg.game_id AND reports.dp_id = dpg.draw_period_id
                    LEFT JOIN games g2 on dpg.game_id = g2.id
                    LEFT JOIN draw_periods dp2 on dpg.draw_period_id = dp2.id
                ");
                break;
        }

//        $reportUrl = URL::temporarySignedRoute('reports.bet.general.generate', now()->addMinutes(30),
//            ['cluster_id' => $validated['cluster_id'], 'draw_period_id' => $validated['draw_period_id'],
//                'game' => $validated['game'], 'dates' => $validated['dates']]);

        return response(['reports' => $reports, 'report_url' => $reportUrl, 'game_abbreviations' => $game_abbreviations],200);
    }
}
