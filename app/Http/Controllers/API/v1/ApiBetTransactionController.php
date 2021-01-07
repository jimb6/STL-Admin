<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\CloseNumber;
use App\Models\ControlCombination;
use App\Models\DrawPeriod;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBetTransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-bet-transactions', BetTransaction::class);
        $search = $request->get('search', '');
        $betTransactions = BetTransaction::search($search)->with('bets')->get()->sortBy('bets.amount');
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
        if (CloseNumber::whereIn('combination', array_column($bets, 'combination'))
            ->whereDate('created_at', Carbon::today())->get()->isNotEmpty()) abort(406);

        $tempVals = [];
        foreach ($bets as $bet) {

            //Check if bet has a valid draw_period  (Pwedi siya dili isulod sa loop)
            $d = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($bet) {
                $query->where('id', $bet['game_id']);
            })->first();

            if (!$d) abort(406);

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
                if (strlen($combination) != $game[0]->digit_per_field_set) abort(406);
                if (intval($combination) > $game[0]->max_per_field_set) abort(406);
                if (intval($combination) < $game[0]->min_per_field_set) abort(406);
            }

            if ($bet['amount'] > $game[0]->max_per_bet) abort(406);
            if ($bet['amount'] < $game[0]->min_per_bet) abort(406);
            if ($b->isNotEmpty() && ($b[$bet['combination']]['sum'] + $bet['amount']) > $game[0]->max_sum_bet) abort(406);

            $control = ControlCombination::where('game_id', $game[0]->game_id)->where('combination', $bet['combination'])->get();

            if ($b->isNotEmpty() && $control->isNotEmpty() && $b != null && $b[ $bet['combination']]['sum'] + $bet['amount'] > $control[0]->max_amount) abort(406);
            array_push($tempVals, array_merge($bet, ['draw_period_id' => $d['id']]));
        }

        $transaction = null;
        try {
            $transaction = BetTransaction::create([
                'user_id' => $agentId
            ]);
        } catch (\Exception $ex) {
            return response(['error' => $ex], 400);
        }

        foreach ($tempVals as $val){
            $bet = Bet::create([
                'combination' => $val['combination'],
                'amount' => $val['amount'],
                'is_rumbled' => $val['is_rumbled'],
                'bet_transaction_id' => $transaction->id,
                'game_id' => $val['game_id'],
                'draw_period_id' => $val['draw_period_id'],
            ]);
        }

        broadcast(new NewBetTransactionAdded(Game::find($game[0]->game_id)));
        return response(['transaction-details'=> $transaction], 202);
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
}
