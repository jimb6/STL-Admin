<?php

namespace App\Http\Controllers\API\v1;

use App\Events\DashboardEvent;
use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\CloseNumber;
use App\Models\ControlCombination;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\GameConfiguration;
use App\Models\WinningCombination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ApiBetTransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $search = $request->get('search', '');
        $betTransactions = BetTransaction::today()->search($search)->with('bets')->get()->sortBy('bets.amount');
        return response(['betTransactions' => $betTransactions], 200);
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
            foreach ($permutedValues as $permutedValue){
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
                return response(['message' => 'Amount exceeds on bet '.$bet['combination'].'. Available amount ' . ($betSum - $gameConfig->max_sum_bet)], 406);

            //Get the control combinations
            $control = ControlCombination::where('game_id', $gameConfig->game_id)->where('combination', $bet['combination'])->sum('max_amount');

            //check the control combinations
            if ($betSum && $control && $betSum + $bet['amount'] > $control)
                return response(['message' => 'Amount exceeds on bet '.$bet['combination'].'. Available amount ' . ($betSum - $control)], 406);
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
        $validated['dates'][1] .= ' 23:59:59';

        $betTransactions = DB::table('bet_transactions as bt')
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
            ->select(DB::raw('SUM(b.amount) as total, GROUP_CONCAT(DISTINCT concat(b.combination,\'=\',b.amount)) as combinations, bt.id, bt.qr_code, bt.is_void, bt.printable, bt.created_at, bt.updated_at, u.name, dp.draw_time'))
            ->orderByDesc('bt.created_at')
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
        $this->authorize('list-bet-transactions', Bet::class);
        $validated = $request->validate([
            'cluster_id' => 'required|array',
            'draw_period_id' => 'required|array',
            'game' => 'required',
            'dates' => 'required|array|max:2|min:2'
        ]);

        $game = Game::with('gameConfiguration')->where('abbreviation', $validated['game'])->first();
        $validated['dates'][1] .= ' 23:59:59';

        $bets = BetTransaction::whereBetween('created_at', $validated['dates'])
            ->whereHas('user', function ($subQuery) use ($validated) {
                $subQuery->whereIn('cluster_id', $validated['cluster_id']);
            })
            ->whereHas('bets', function ($query) use ($game, $validated) {
                $query->where('game_id', $game->id)
                    ->whereIn('draw_period_id', $validated['draw_period_id']);
            })->with('user.cluster', 'bets.drawPeriod')->get();


        $winningCombinations = WinningCombination::where('game_id', $game->id)
            ->whereIn('draw_period_id', $validated['draw_period_id'])
            ->whereBetween('created_at', $validated['dates'])
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

        $reportUrl = URL::temporarySignedRoute('reports.bet.general.generate',
            now()->addMinutes(30),
            ['cluster_id' => $validated['cluster_id'], 'draw_period_id' => $validated['draw_period_id'],
                'game' => $validated['game'], 'dates' => $validated['dates']]);

        return response(['bets' => $bets, 'report_url' => $reportUrl], 200);
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
}
