<?php

namespace App\Http\Controllers\API\v1;

use App\Events\DashboardEvent;
use App\Events\NewBetTransactionAdded;
use App\Exports\GeneralReports;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\CloseNumber;
use App\Models\ControlCombination;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\WinningCombination;
use App\Scopes\TransactionBaseScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function create(Request $request)
    {
        $this->authorize('create-bet-transactions', BetTransaction::class);
        return response([], 200);
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

        $bets = $validated['bets'];
        $agentId = $request->user('sanctum')->id;

        //Check Close number
        $closeNumbers = CloseNumber::whereIn('combination', array_column($bets, 'combination'))
            ->whereDate('created_at', Carbon::today())->get();
        if ($closeNumbers->isNotEmpty()) return response(['message' => 'Some numbers are closed. ' . $closeNumbers], 406);

        $tempVals = [];
        foreach ($bets as $bet) {

            //Check if bet has a valid draw_period  (Pwedi siya dili isulod sa loop)
            $d = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($bet) {
                $query->where('id', $bet['game_id']);
            })->first();

            if (!$d) return response(['message' => 'Cannot processed the bets. Game in progress.'], 406);

            //Get the Sum of bets amount from the same submitted Bets
            $b = Bet::currentDraw()->where('game_id', $bet['game_id'])
                ->where('combination', $bet['combination'])
                ->get()->groupBy('combination')->map(function ($row) {
                    return ['sum' => $row->sum('amount'), 'bets' => $row];
                });

            //Get Game Configuration to compare the maximum and minimum bets amount
            $game = Game::where('id', $bet['game_id'])->with('gameConfiguration')->get()
                ->map(function ($row) {
                    return $row->getRelation('gameConfiguration');
                });


            // Comparing....
            // COMBI >>>>       01-50
            // Pares range >>>  1-38
            // COMBI >>>>       123-R

            $combinations = explode('-', $bet['combination']);
            if ((count(array_unique($combinations)) != count($combinations)) && !$game[0]->has_repetition) abort(406);
            foreach ($combinations as $combination) {
                if (strlen($combination) != $game[0]->digit_per_field_set) return response(['message' => 'Invalid combination.'], 406);
                if (intval($combination) > $game[0]->max_per_field_set) return response(['message' => 'Invalid combination.'], 406);
                if (intval($combination) < $game[0]->min_per_field_set) return response(['message' => 'Invalid combination.'], 406);
            }

            //Check the maximum amount per bet
            if ($bet['amount'] > $game[0]->max_per_bet)
                return response(['message' => 'Amount exceeds the maximum. might be less than or equal ' . $game[0]->max_per_bet], 406);
            //Check the minimum amount per bet
            if ($bet['amount'] < $game[0]->min_per_bet)
                return response(['message' => 'Invalid amount. Must be greater than or equal ' . $game[0]->min_per_bet], 406);
            //Check the total amount of the bet
            if ($b->isNotEmpty() && ($b[$bet['combination']]['sum'] + $bet['amount']) > $game[0]->max_sum_bet)
                return response(['message' => 'Amount exceeds. Available amount ' . ($b[$bet['combination']]['sum'] - $game[0]->max_sum_bet)], 406);

            //Get the control combinations
            $control = ControlCombination::where('game_id', $game[0]->game_id)->where('combination', $bet['combination'])->get();

            //check the control combinations
            if ($b->isNotEmpty() && $control->isNotEmpty() && $b != null && $b[$bet['combination']]['sum'] + $bet['amount'] > $control[0]->max_amount)
                return response(['message' => 'Amount exceeds. Available amount ' . ($b[$bet['combination']]['sum'] - $control[0]->max_amount)], 406);
            array_push($tempVals, array_merge($bet, ['draw_period_id' => $d['id']]));
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


        broadcast(new NewBetTransactionAdded(Game::find($game[0]->game_id)));
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

        if(!$game = Game::where('abbreviation', $validated['game'])->first()) abort(400);
        $validated['dates'][1] .= ' 23:59:59';
        $betTransactions = BetTransaction::whereBetween('created_at', $validated['dates'])
            ->whereHas('bets', function ($query) use ($game, $validated) {
                $query->whereIn('draw_period_id', $validated['draw_periods'])->where('game_id', $game->id);
            })->with('bets', 'user')->orderByDesc('created_at')->get();

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
//        $validated = $request->validate([
//            'cluster_id' => 'required|array',
//            'draw_period_id' => 'required|array',
//            'game' => 'required',
//            'dates' => 'required|array|max:2|min:2'
//        ]);
        $reportUrl = URL::temporarySignedRoute('reports.bets.history.generate',
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
        BetTransaction::find($id)->update($validated);
        return response([], 204);
    }

//    public function permutateNumber($elements, $perm = array(), &$permArray = array())
//    {
//        if (empty($elements)) {
//            array_push($permArray, $perm);
//            return;
//        }
//        for ($i = 0; $i <= count($elements) - 1; $i++) {
//            array_push($perm, $elements[$i]);
//            $tmp = $elements;
//            array_splice($tmp, $i, 1);
//            $this->permutateNumber($tmp, $perm, $permArray);
//            array_pop($perm);
//        }
//        return $permArray;
//    }

    public function swap($res, $a, $b)
    {
        $tmp = $res[$a];
        $res[$a] = $res[$b];
        $res[$b] = $tmp;
        return $res;
    }

    public function permutate($list, $index = 0)
    {
        if (count($list) - 1 <= $index) return [array_splice($list, 0)];
        $res = [];
        for ($i = $index; $i < count($list); $i++) $res = array_merge($res, $this->permutate($this->swap($list, $i, $index), $index + 1));
        return array_map("unserialize", array_unique(array_map("serialize", $res)));
    }
}
