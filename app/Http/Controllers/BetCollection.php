<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\v1\Controller;
use App\Models\Bet;
use App\Models\BetTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BetCollection extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function baseCollection()
    {
        $userBaseId = User::find(1)->base()->get()[0]['id'];
        $betTransaction = BetTransaction::with('agents')->get();
//        with('agent')->where('agent.base_id', '=', $userBaseId)->get();

//        $betCollections = $betTransactions->sum('amount');

//        $sum =0 ;
//        foreach ($collections[0]->bets as $bet)
//        {
//            $sum+=$bet->amount;
//        }

        return response([$betTransaction], 200);
    }
}
