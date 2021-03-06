<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use Illuminate\Http\Request;

class ApiBetController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list bet transactions', Bet::class);
        $search = $request->get('search', '');
        $bets = Bet::with(['game', 'drawPeriod', 'betTransaction'])->get();
        return response(['bets' => $bets], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create bet transactions', Bet::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create bet transactions', Bet::class);
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
        $this->authorize('view bet transactions', Bet::class);
        return response([], 204);
    }

    public function edit(Request $request, Bet $bet)
    {
        $this->authorize('update bet transactions', $bet);
        return response(['bets' => $bet], 200);
    }

    public function update(Request $request, Bet $bet)
    {
        $this->authorize('update bet transactions', $bet);
        $validated = $request->validated();
        $bet->update($validated);
        return response([$bet], 202);
    }

    public function destroy(Request $request, Bet $bet)
    {
        $this->authorize('delete bet transactions', $bet);
        $bet->delete();
        return response([], 204);
    }


    public function getBetsPerformancePerDrawPeriod()
    {

    }
}
