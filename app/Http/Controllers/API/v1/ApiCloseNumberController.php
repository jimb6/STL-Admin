<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NewBetTransactionAdded;
use App\Http\Controllers\Controller;
use App\Models\CloseNumber;
use App\Models\DrawPeriod;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCloseNumberController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->can('list-close-numbers', CloseNumber::class);
        $search = $request->get('search', '');
        $closeNumbers = CloseNumber::with(['game', 'drawPeriod'])->get();
        return response(['closeNumbers' => $closeNumbers], 200);
    }

    public function create(Request $request)
    {
        $request->user()->can('create-close-numbers', CloseNumber::class);
        return response([], 200);
    }

    public function store(Request $request, $game, $drawPeriod)
    {
        $request->user()->can('create-close-numbers', CloseNumber::class);
        $validated = $request->validate([
            'combinations' => 'required'
        ]);

        $closeNumber = CloseNumber::firstOrCreate([
            'combination' => $validated['combinations'],
            'draw_period_id' => $drawPeriod,
            'game_id' => $game,
        ]);

        return response(['closeNumber' => $request->all()], 202);
    }

    public function show(Request $request, CloseNumber $closeNumber)
    {
        $request->user()->can('view-close-numbers', CloseNumber::class);
        return response([], 204);
    }

    public function edit(Request $request, CloseNumber $closeNumber)
    {
        $request->user()->can('update-close-numbers', $closeNumber);
        return response(['closeNumbers' => $closeNumber], 200);
    }

    public function update(Request $request, CloseNumber $closeNumber)
    {
        $request->user()->can('update-close-numbers', $closeNumber);
        $validated = $request->validated();
        $closeNumber->update($validated);
        return response([$closeNumber], 202);
    }

    public function destroy(Request $request, $game, $drawPeriod)
    {
        $request->user()->can('delete-close-numbers', CloseNumber::class);
        $validated = $request->validate([
            'combinations' => 'required'
        ]);
        $closeNumber = CloseNumber::where('combination', $validated['combinations'])
            ->where('game_id', $game)->where('draw_period_id', $drawPeriod)
            ->forceDelete();
//        broadcast(new NewBetTransactionAdded(Game::find($game)));
        return response([$game, $drawPeriod], 200);
    }

}
