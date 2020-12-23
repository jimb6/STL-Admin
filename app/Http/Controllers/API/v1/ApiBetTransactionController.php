<?php

namespace App\Http\Controllers\API\v1;

use App\Events\BetTransactionAdded;
use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\CloseNumber;
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

        $hasCloseNumbers = CloseNumber::whereIn('combination', array_column($bets, 'combination'))->count();
        $allClose = CloseNumber::all();
        if ($hasCloseNumbers > 0)
        {
            return response(['close_numbers' => $allClose], 406);
        }
        $transaction = BetTransaction::create([
            'user_id' => $agentId
        ]);

        $saveBets = [];
        foreach ($bets as $bet) {
            $bet['bet_transaction_id'] = $transaction->id;
            if ($bet['is_rumbled']) {
                $p = $this->permutate(str_split($bet['combination']));
                $result = array();
                foreach ($p as $perm) {
                    $result[] = join("", $perm);
                }
                $result = array_unique(array_unique($result));
                $close = CloseNumber::whereIn('combination', $result)->count();
                if ($close>0){
                    return response(["message" => 'Bets contains close numbers.', "numbers" => $allClose],406);
                }
                $originalAmount = $bet['amount'];
                foreach (array_values($result) as $array_value) {
                    array_push($saveBets, $result);
                    $bet['combination'] = $array_value;
                    $bet['amount'] = number_format($originalAmount / count($result), 2, '.', ',');
                    if ($save = Bet::create($bet)) {
                        array_push($saveBets, $save);
                    }
                }
            }else{
                if ($save = Bet::create($bet)) {
                    array_push($saveBets, $save);
                }
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
    public function swap($res,$a,$b){
        $tmp =$res[$a];
        $res[$a] = $res[$b];
        $res[$b] = $tmp;
        return $res;
    }
    public function permutate($list,$index=0){
        if(count($list)-1<=$index) return [array_splice($list,0)];
        $res = [];
        for($i = $index;$i<count($list);$i++)$res = array_merge($res,$this->permutate($this->swap($list,$i,$index),$index+1));
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
