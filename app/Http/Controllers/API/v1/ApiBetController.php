<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\CloseNumber;
use App\Models\Game;
use App\Scopes\BetScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBetController extends Controller
{
    public function index(Request $request, $game, $draw)
    {
        $this->authorize('list-bets', Bet::class);
        $game = Game::where('id', $game)
            ->with(['gameConfiguration', 'controlCombination', 'bets'])
            ->get();
        $bets = Bet::currentDraw()->where('game_id', $game[0]->id)->get()
            ->groupBy('combination')->map(function ($row) {
                return ['sum' => $row->sum('amount'), 'bets' => $row];
            });
        $closedNumbers = CloseNumber::all();
        return response(['bets' => $bets, 'closed_numbers' => $closedNumbers], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create-bet-transactions', Bet::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-bet-transactions', Bet::class);
        $validated = $request->validate([
            'agent_id' => 'required',
            'bets.*' => 'required',
            'bets.*.amount' => 'required|numeric|min:1|max:10000',
            'bets' => 'required|array|min:1|max:10',
        ]);

        $bets = $validated['bets'];
        $agentId = $validated['agent_id'];

//        $hasCloseNumbers =
        $transaction = Bet::create([
            'user_id' => $agentId
        ]);

        $saveBets = [];
//        DB::table('bets')->insert($bets)
        foreach ($bets as $bet) {
            $bet['bet_transaction_id'] = $transaction->id;
//            array_merge($bet, ['bet_transaction_id'=>$transaction->id]);
            if ($save = Bet::create($bet)) {
                array_push($saveBets, $save);
            }
        }
        event(new BetTransactionAdded($transaction));
        return response(['bets' => $saveBets, 'code' => $transaction->id], 202);
    }

    public function show(Request $request, Bet $bet)
    {
        $this->authorize('view-bet-transactions', Bet::class);
        return response([], 204);
    }

    public function edit(Request $request, Bet $bet)
    {
        $this->authorize('update-bet-transactions', $bet);
        return response(['bets' => $bet], 200);
    }

    public function update(Request $request, Bet $bet)
    {
        $this->authorize('update-bet-transactions', $bet);
        $validated = $request->validated();
        $bet->update($validated);
        return response([$bet], 202);
    }

    public function destroy(Request $request, Bet $bet)
    {
        $this->authorize('delete-bet-transactions', $bet);
        $bet->delete();
        return response([], 204);
    }

    public function getBetsRange(Request $request, $game, $date)
    {
        $this->authorize('list-bet-transactions', Bet::class);
        $bets = Bet::withoutGlobalScope(BetScope::class)
            ->where('created_at', 'like', "{$date}%")
            ->with(['betTransaction.user', 'game', 'drawPeriod'])->get();
        $bets = $bets->groupBy('combination')->map(function ($row) {
            return ['sum' => $row->sum('amount'), 'bets' => $row];
        });
        $closeNumbers = CloseNumber::with(['game', 'drawPeriod'])->get();
        return response(['bets' => $bets, 'closeNumbers' => $closeNumbers], 200);
    }


    public function getCombinationBetsReport(Request $request)
    {
        $this->authorize('list-bet-transactions', Bet::class);
        $validated = $request->validate([
            'cluster_id' => 'required|array',
            'draw_period_id' => 'required|array',
            'game' => 'required',
            'dates' => 'required|array|max:2|min:2'
        ]);

        $validated['dates'][1] .= ' 23:59:59';
        $bets = Bet::whereBetween('created_at', $validated['dates'])
            ->whereIn('draw_period_id', $validated['draw_period_id'])
            ->with(['betTransaction.user' => function ($query) use ($validated) {
                $query->whereIn('cluster_id', $validated['cluster_id']);
            }, 'game' => function($query) use ($validated) {
                $query->where('abbreviation', $validated['game']);
            }, 'drawPeriod'])
            ->get();
        $bets = $bets->reject(function ($item, $key){
            return $item['game'] == null;
        });
        $bets = $bets->groupBy('combination')->map(function ($row) {
            return ['sum' => $row->sum('amount'), 'bets' => $row];
        });

        return response(['bets' => $bets], 200);
    }
}
