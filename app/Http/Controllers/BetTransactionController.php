<?php

namespace App\Http\Controllers;

use App\Models\BetTransaction;
use App\Models\ClosedNumbers;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Request;
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
        $this->middleware('auth:web');
    }

    public function index()
    {

    }

    public function show()
    {
        $gameCategory = request('any');
        $bets = BetTransaction::with('gameCategory', 'drawPeriod')->where('gameCategory.abbreviation', '=')
        if ($gameCategory !== '')
        {
            echo 'naay sulod..';
        }
        return view('bets.categorize', );
    }

    public function show3D(Request $request)
    {

    }

    public function show4D(Request $request)
    {

    }

    public function showPares(Request $request)
    {


    }

    public function showPick3(Request $request)
    {

    }
}
