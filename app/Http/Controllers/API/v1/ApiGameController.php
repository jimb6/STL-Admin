<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class ApiGameController extends Controller
{
    public function index(Request $request, $abbreviation)
    {
        $this->authorize('list games', Game::class);
        $search = $request->get('search', '');
        $games = Game::where('abbreviation', $abbreviation)->with(['drawPeriods', 'controlCombination', 'bets', 'gameConfiguration'])->get();
        return response(['games' => $games], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create games', Game::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create games', Game::class);
        $validated = $request->validate([
            'description' => 'required',
            'abbreviation' => 'required',
            'prize' => 'required',
            'field_set' => 'required',
            'digit_per_field_set' => 'required',
            'min_number' => 'required',
            'max_number' => 'required',
            'has_repetition' => 'required',
            'days_availability.*' => 'required',
        ]);

        $game = Game::create([
            'description' => $validated['description'],
            'abbreviation' => $validated['abbreviation'],
            'prize' => $validated['prize'],
            'field_set' => $validated['field_set'],
            'digit_per_field_set' => $validated['digit_per_field_set'],
            'min_number' => $validated['min_number'],
            'max_number' => $validated['max_number'],
            'has_repetition' => $validated['has_repetition']=='True',
            'days_availability' => $validated['days_availability'],
        ]);

        return response(['game' => $validated], 200);
    }

    public function show(Request $request, Game $games)
    {
        $this->authorize('view games', $games);
        return response([$games], 200);
    }

    public function edit(Request $request, Game $game)
    {
        $this->authorize('update games', $game);
        return response(['game' => $game], 200);
    }

    public function update(Request $request, Game $game)
    {
        $this->authorize('update games', $game);
        $validated = $request->validated();
        $game->update($validated);
        return response(['game' => $game], 202);
    }

    public function destroy(Request $request, Game $game)
    {
        $this->authorize('delete games', $game);
        $game->delete();
        return response([], 204);
    }
}
