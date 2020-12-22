<?php

namespace App\Http\Controllers\API\v1;

use App\Events\BetTransactionAdded;
use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use Illuminate\Http\Request;

class ApiBetTransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list bet transactions', BetTransaction::class);
        $search = $request->get('search', '');
        $betTransactions = BetTransaction::search($search)->with('bets')->get()->sortBy('bets.amount');
        return response(['betTransactions' => $betTransactions], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create bet transactions', BetTransaction::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create bet transactions', BetTransaction::class);
        //Validate the user inputs
        $validated = $request->validate([
            'bets.*' => 'required',
            'bets.*.amount' => 'required|numeric|min:1|max:10000',
            'bets' => 'required|array|min:1|max:10',
        ]);

        $bets = $validated['bets'];
        $agentId = $request->user('sanctum')->id;

//        $hasCloseNumbers =
        $transaction = BetTransaction::create([
            'user_id' => $agentId
        ]);

        $saveBets = [];
//        DB::table('bets')->insert($bets)
        foreach ($bets as $bet)
        {
            $bet['bet_transaction_id'] = $transaction->id;
//            array_merge($bet, ['bet_transaction_id'=>$transaction->id]);
            if($save = Bet::create($bet)){
                array_push($saveBets, $save);
            }
        }

        broadcast(new NewBetTransactionAdded($transaction));
        return response(['bets' => $bets, 'code' => $transaction->id], 202);
    }

    public function show(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('view bet transactions', BetTransaction::class);
        return response([], 204);
    }

    public function edit(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update bet transactions', $betTransaction);
        return response(['betTransactions' => $betTransaction], 200);
    }

    public function update(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update bet transactions', $betTransaction);
        $validated = $request->validated();
        $betTransaction->update($validated);
        return response([$betTransaction], 202);
    }

    public function destroy(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('delete bet transactions', $betTransaction);
        $betTransaction->delete();
        return response([], 204);
    }
}
