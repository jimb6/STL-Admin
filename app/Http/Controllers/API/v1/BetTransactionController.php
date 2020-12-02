<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BetTransaction;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BetTransactionController extends Controller
{
    //

    /**
     * BetTransactionController constructor.
     */
    public function __construct()
    {
//        $this->middleware(['agent']);
    }

    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                'agent.*' => 'required',
                'booth.*' => 'required',
                'transaction.transaction_date' => 'required|date|after:yesterday|before:tomorrow',
                'bets' => 'required | array | min:1 | max:10',
                'bets.*.amount' => 'numeric|min:1|max:1000'
            ]);



        $bets = $validated['bets'];
        $tran_date = $validated['transaction']['transaction_date'];
        $agent_id = $validated['agent']['agent_id'];
        $booth_id = $validated['booth']['booth_id'];



        //Check if the numbers are closed
//        Get Bet Games (2D, 4D, 3D)
//        Get Combinations (223, 334)
//        Check if number is closed
//        $combinations = array_values(array_column($bets, 'combination'));
//        $hasCloseNumber = ClosedNumbers::whereIn('number_value', $combinations)->get();
//
//
//         SELECT * CLOSE NUMBER WHERE BETGAMEID = BET_BETGAME_ID > 0 ? NAAY CLOSE : WALA GI CLOSE
//

//        STL-2D, STL-3D PARES, NAT. 2D, NAT. 3D, NAT.4D, PICK -3
//        STL (10AM, 3PM, 9PM - 2PM 5PM 9PM)
//
//
//
//                           //Eloquent Performance 156ms per transaction
        $hasCloseNumber = DB::table('closed_numbers')->whereIn('number_value', $combinations)->get();   //Query Builder Performance 132ms per transaction
        if (count($hasCloseNumber) > 0) {
            //Validation of request
            return $request->wantsJson() ? new JsonResponse(['message' => 'Transaction failed!', 'closed_numbers' => $hasCloseNumber], 200) : view('home');
        }
//        $transactionCode = Str::orderedUuid();
        $transaction = Transaction::create([
            'agent_id' => $agent_id,
            'booth_id' => $booth_id,
            'transaction_date' => $tran_date]);

        $savedBets = [];
        foreach ($bets as $bet) {
            $save = BetTransaction::create([
                'transaction_id' => $transaction->id,
                'game_category_id' => $bet['game_category_id'],
                'draw_period_id' => $bet['draw_period_id'],
                'combination' => $bet['combination'],
                'amount' => $bet['amount'],
            ]);
//            return $save->id;
            $save = BetTransaction::where(['id' => $save->id])->with(['gameCategories','drawPeriod.gameType'])->get();
            array_push($savedBets, $save[0]);
        }

//        Encrypt the transaction ID so that it would be more safer....
        $encryptedKey = \Crypt::encrypt($transaction->id);
        return $request->wantsJson() ? new JsonResponse([
            'transaction_code' => $encryptedKey,
            'message' => 'Transaction granted!',
            'data' => $savedBets,
            ],
            200) : view('home');
    }

    public function validateTransaction(Request $request)
    {

        $decryptedKey = \Crypt::decrypt(request('transaction_code'));
        return Transaction::with(['bets', 'agent', 'booth'])->where('id', $decryptedKey)->get();
    }
}
