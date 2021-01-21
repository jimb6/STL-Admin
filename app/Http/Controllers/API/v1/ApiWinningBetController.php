<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BetTransaction;
use App\Models\User;
use App\Models\WinningBet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiWinningBetController extends Controller
{

    public function index()
    {

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
        $this->authorize('update-bet-transactions', BetTransaction::class);
        $user = $request->user();
        if (!$code2 = DB::table('bet_transactions as bt')
            ->select(DB::raw("bt.id"))
            ->leftJoin('bets as b', 'bt.id', '=', 'b.bet_transaction_id')
            ->leftJoin('game_configurations as gc', 'b.game_id', '=', 'gc.game_id')
            ->where('bt.qr_code', $code)
            ->first()) return response(["message" =>  [(object)["winning_bet_status" => "Can't validate the betting transaction."]]], 406);

        $verified = DB::table('bet_transactions as bt')
            ->select(DB::raw("wb.id as winning_id, bt.id, b.amount, b.combination, wb.status,
                                    wb.created_at as win_date, dp.draw_time, g.abbreviation as game_name,
                                    bt.qr_code, b.amount * gc.multiplier as payable"))
            ->leftJoin('bets as b', 'bt.id', '=', 'b.bet_transaction_id')
            ->leftJoin('draw_periods as dp', 'dp.id', '=', 'b.draw_period_id')
            ->leftJoin('games as g', 'g.id', '=', 'b.game_id')
            ->leftJoin('game_configurations as gc', 'b.game_id', '=', 'gc.game_id')
            ->rightJoin('winning_bets as wb', 'wb.bet_id', '=', 'b.id')
            ->where('bt.qr_code', $code)
            ->where('bt.user_id', $user->id)
            ->get();
        if (!$verified->isNotEmpty()) return response(["message" =>  [(object)["winning_bet_status" => "There is no winning bet for transaction code {$code}"]]], 406);

        $mResponse = [];
        for ($i=0; $i<count($verified); $i++) {
            if( $verified[$i]->status ) {
                $verified[$i]->winning_bet_status = "Claimed";
                continue;
            }
            if ($status = WinningBet::where('id', $verified[$i]->winning_id)->update(['status'=> true])){
                $verified[$i]->status = $status;
                $verified[$i]->date_claimed = Carbon::now(config('app.timezone'))->format('F j, Y');
                $verified[$i]->win_date = Carbon::parse($verified[$i]->win_date, config('app.timezone'))->format('F j, Y');
                $verified[$i]->time_claimed = Carbon::now(config('app.timezone'))->format('g:i A');
                $verified[$i]->draw_time = Carbon::parse($verified[$i]->draw_time, config('app.timezone'))->format('g:i A');
                $verified[$i]->winning_bet_status = "Verified";
            }
        }

        return response(["message" => $verified], 200);
    }

    public function showByAgent(Request $request, $date)
    {
        $this->authorize('list-winning-combinations', WinningBet::class);
        if (!$user = $request->user()) return abort(401);
        if (!$user->hasRole('agent')) return abort(401);
//        $user = $request->user();
        $betTransactions = DB::table('bet_transactions as bt')
            ->select(DB::raw("bt.id, gc.multiplier, wb.status, bt.created_at as trans_time, dp.draw_time as draw_period,
                                    g.abbreviation as game_name, b.combination, b.amount, bt.qr_code"))
            ->where('bt.user_id', $user->id)
            ->whereDate(DB::raw('DATE(bt.created_at)'), $date)
            ->leftJoin('bets as b', 'b.bet_transaction_id', '=', 'bt.id')
            ->join('winning_bets as wb', 'wb.bet_id', '=', 'b.id')
            ->leftJoin('draw_periods as dp', 'b.draw_period_id', '=', 'dp.id')
            ->leftJoin('games as g', 'b.game_id', '=', 'g.id')
            ->leftJoin('game_configurations as gc', 'gc.game_id', '=', 'g.id')
            ->orderByDesc('bt.id')
            ->get()->groupBy('id');

        return response(['betTransactions' => $betTransactions], 200);

    }
}
