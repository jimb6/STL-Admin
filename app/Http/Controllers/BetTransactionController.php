<?php

namespace App\Http\Controllers;

use App\Models\BetTransaction;
use App\Models\ClosedNumbers;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'booth_code' => 'required',
                'agent_name' => 'required',
                'agent_code' => 'required',
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
        $transactionCode = Str::orderedUuid();
        Transaction::insert([
            'transaction_code' => $transactionCode,
            'agent_id' => $agent_id,
            'booth_id' => $booth_id,
            'transaction_date' => $tran_date]);

        foreach ($bets as $bet) {
            BetTransaction::insert([
                'transaction_code' => $transactionCode,
                'game_category' => $bet['game_category'],
                'draw_period' => $bet['draw_period'],
                'combination' => $bet['combination'],
                'amount' => $bet['amount'],
            ]);
        }

        return $request->wantsJson() ? new JsonResponse(['transaction_code' => $transactionCode, 'success' => 'Transaction granted!', 'data' => $bets], 200) : view('home');

    }

    public function validateTransaction(Request $request)
    {
        return Transaction::where('transaction_code', request('transaction_code'))->get();
    }
}
