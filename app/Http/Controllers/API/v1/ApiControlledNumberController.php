<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NewControlledBetAdded;
use App\Http\Controllers\Controller;
use App\Models\ControlCombination;
use App\Models\Game;
use App\Models\GameConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiControlledNumberController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $game)
    {
        $request->user()->can('create-control-combinations', ControlCombination::class);
        $validated = $request->validate([
            "combination" => "required",
            "max_amount" => "required"
        ]);
        $game = Game::where('abbreviation', $game)->first();
        $gameConfig = GameConfiguration::where('game_id', $game->id)->first();

        $combinations = explode('-', $validated['combination']);
        if ((count(array_unique($combinations)) != count($combinations)) && !$game[0]->has_repetition) abort(406);
        foreach ($combinations as $combination) {
            if (strlen($combination) != $gameConfig->digit_per_field_set) abort(406);
            if (intval($combination) > $gameConfig->max_per_field_set) abort(406);
            if (intval($combination) < $gameConfig->min_per_field_set) abort(406);
        }

        ControlCombination::create([
            'game_id' => $game->id,
            'combination' => $request->get('combination'),
            'max_amount' => $request->get('max_amount')
        ]);
        broadcast(new NewControlledBetAdded($game));
        return response([], 202);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, ControlCombination $controlCombination, $game)
    {
        $request->user()->can('update-control-combinations', $controlCombination);
        $validated = $request->validate([
            'id' => 'required',
            "combination" => "required",
            "max_amount" => "required"
        ]);
        $game = Game::where('abbreviation', $game)->first();
        $gameConfig = GameConfiguration::where('game_id', $game->id)->first();

        $splitValues = explode('-', $request->get('combination'));
        if(count($splitValues) != $gameConfig->field_set) abort(406);
        if (count($splitValues) != count(array_unique($splitValues))) abort(406);
        foreach ($splitValues as $splitValue) {
            if (strlen($splitValue) != $gameConfig->digit_per_field_set) abort(406);
        }
        $controlCombination = ControlCombination::find($validated['id'])
            ->update(['combination' => $validated['combination'], 'max_amount' => $validated['max_amount']]);
        broadcast(new NewControlledBetAdded($game));
        return response([], 204);
    }

    public function destroy(Request $request, ControlCombination $combi)
    {
        $request->user()->can('delete-control-combinations', $combi);
        $combi->delete();
        broadcast(new NewControlledBetAdded(Game::find($combi->game_id)));
        return response([], 204);
    }
}
