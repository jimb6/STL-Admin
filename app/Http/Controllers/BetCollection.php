<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Base;
use App\Models\Bet;
use App\Models\BetGame;
use App\Models\BetTransaction;
use App\Models\DrawPeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetCollection extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|Factory|View|Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function baseCollection()
    {
        try {
//          GET 10:30 2D Bets
            $data = DB::table('bets_today')
                ->where('bet_game_id', '=', 2)
                ->where('draw_period_id', '=', 1)
                ->sum('amount');
        }catch (\Exception $ex)
        {
            DB::select('call setBetsToday');
            $data = DB::table('bets_today')->where('bet_game_id', '=', 2)->get();
        }

//        $data = Bet::with('drawPeriod', 'betGame')->where('created_at', '')->get();

        return response([$data], 200);

    }

    public function todayBaseCollection()
    {
        $data = DB::select('call sumDailyCollection(?)', [1]);
        return response([$data], 200)->header('Content-Type', 'application/json');
    }
}
