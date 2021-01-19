<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
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
        //
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
}
