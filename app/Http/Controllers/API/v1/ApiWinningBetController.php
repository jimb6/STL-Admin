<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BetTransaction;
use App\Models\User;
use App\Models\WinningBet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiWinningBetController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function showByDate(Request $request, $date){
//        $this->authorize('list-winning-combinations', WinningBet::class);
        if (Auth::check()) $user = Auth::user();
        else return abort(401);
        if ($user->hasRole('super-admin'))
            $winningBets = DB::select("
            SELECT u.name                as agent,
                bt.qr_code               as transaction_code,
                DATE(bt.created_at)      as draw_date,
                dp.draw_time             as draw_period,
                g.abbreviation           as game,
                b.combination            as combination,
                b.amount                 as amount,
                b.amount * gc.multiplier as payable,
                wb.status as claimed
            from winning_bets wb
                left join bets b on b.id = wb.bet_id
                left join draw_periods dp on dp.id = b.draw_period_id
                left join bet_transactions bt on bt.id = b.bet_transaction_id
                left join users u on u.id = bt.user_id
                left join games g on g.id = b.game_id
                left join game_configurations gc on g.id = gc.game_id
                where DATE(wb.created_at) = DATE('{$date}')");
        else
            $winningBets = DB::select("
            SELECT u.name                as agent,
                bt.qr_code               as transaction_code,
                DATE(bt.created_at)      as draw_date,
                dp.draw_time             as draw_period,
                g.abbreviation           as game,
                b.combination            as combination,
                b.amount                 as amount,
                b.amount * gc.multiplier as payable,
                wb.status as claimed
            from winning_bets wb
                left join bets b on b.id = wb.bet_id
                left join draw_periods dp on dp.id = b.draw_period_id
                left join bet_transactions bt on bt.id = b.bet_transaction_id
                left join users u on u.id = bt.user_id and u.cluster_id = {$user->cluster_id}
                left join games g on g.id = b.game_id
                left join game_configurations gc on g.id = gc.game_id
                where DATE(wb.created_at) = DATE('{$date}')");

        return response(['winning_bets' => $winningBets], 200);
    }

    public function verifyBetTransaction(Request $request, $code)
    {
        $this->authorize('verify-bet-transactions', BetTransaction::class);
        if (!$code2 = DB::table('bet_transactions as bt')
            ->select(DB::raw("bt.id"))
            ->leftJoin('bets as b', 'bt.id', '=', 'b.bet_transaction_id')
            ->leftJoin('game_configurations as gc', 'b.game_id', '=', 'gc.game_id')
            ->where('bt.qr_code', $code)
            ->first()) return response(["message" => "Can't validate the betting transaction."]);

        $verified = DB::table('winning_bets as wb')
            ->select(DB::raw("wb.status, wb.created_at, b.combination, b.amount"))
            ->rightJoin('bets as b', function ($query) use ($code2) {
                $query->whereColumn('b.id', '=', 'wb.bet_id')
                    ->where('b.bet_transaction_id', '=', $code2->id);
            })
            ->whereNotNull('wb.id')
            ->get();

        $verified = DB::table('bet_transactions as bt')
            ->select(DB::raw("bt.id, b.amount, b.combination, wb.status, b.amount * gc.multiplier as payable"))
            ->leftJoin('bets as b', 'bt.id', '=', 'b.bet_transaction_id')
            ->leftJoin('game_configurations as gc', 'b.game_id', '=', 'gc.game_id')
            ->rightJoin('winning_bets as wb', 'wb.bet_id', '=', 'b.id')
            ->where('bt.qr_code', $code)
            ->get();

        if (!$verified->isNotEmpty()) return response(["message" => "There is no winning bet for transaction code {$code}"]);
        return response(["message" => $verified], 200);
    }
}
