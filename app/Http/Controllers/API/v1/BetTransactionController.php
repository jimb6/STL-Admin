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
                'booth_id' => 'required',
                'agent_id' => 'required',
                'agent_name' => 'required',
                'transactions' => 'required | array | min:1 | max:10',
                'transaction_date' => 'required|date|after:yesterday|before:tomorrow',
                'transactions.*.*' => 'required',
                'transactions.*.amount' => 'numeric|min:1|max:1000'
            ]);

        $bets = $validated['transactions'];
        $tran_date = $validated['transaction_date'];
        $agent_id = $validated['agent_id'];
        $booth_id = $validated['booth_id'];


        //Check if the numbers are closed
        $combinations = array_values(array_column($bets, 'combination'));
//        $hasCloseNumber = ClosedNumbers::whereIn('number_value', $combinations)->get();                           //Eloquent Performance 156ms per transaction
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

        foreach ($bets as $bet) {
            BetTransaction::insert([
                'transaction_id' => $transaction->id,
                'game_category' => $bet['game_category'],
                'draw_period' => $bet['draw_period'],
                'combination' => $bet['combination'],
                'amount' => $bet['amount'],
            ]);
        }

//        Encrypt the transaction ID so that it would be more safer....
        $encryptedKey = \Crypt::encrypt($transaction->id);
        return $request->wantsJson() ? new JsonResponse(['transaction_code' => $encryptedKey, 'success' => 'Transaction granted!', 'data' => $bets], 200) : view('home');
    }

    public function validateTransaction(Request $request)
    {

        $decryptedKey = \Crypt::decrypt(request('transaction_code'));
        return Transaction::with(['bets', 'agent', 'booth'])->where('id', $decryptedKey)->get();
    }
}
