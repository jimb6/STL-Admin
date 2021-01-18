<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\WinningCombination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiWinningCombinationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-winning-combinations', WinningCombination::class);
        $search = $request->get('search', '');
        $winningCombinations = WinningCombination::search($search)->with(['game', 'draw_period'])->get();
        return response(['winningCombination' => $winningCombinations], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create-winning-combinations', WinningCombination::class);

        return response([], 200);
    }

    public function show(Request $request)
    {
        $this->authorize('view-winning-combinations', WinningCombination::class);
        $validated = $request->validate([
            'game' => 'required',
            'dates' => 'required|array|min:2|max:2'
        ]);

        $search = $request->get('search', '');
        $game = Game::where('abbreviation', $validated['game'])->first();
        $winningCombinations = WinningCombination::search($search)
            ->where('game_id', $game->id)
            ->whereBetween('created_at',
                [Carbon::parse($validated['dates'][0])->startOfDay(),
                    Carbon::parse($validated['dates'][1])->endOfDay()])
            ->with(['game', 'drawPeriod'])->get();
        return response(['winningCombination' => $winningCombinations], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-winning-combinations', WinningCombination::class);
        $validated = $request->validate([
            'password' => 'required',
            'combination' => 'required',
            'game' => 'required',
            'drawPeriodId' => 'required'
        ]);

        if (!Hash::check($validated['password'], Auth::user()->getAuthPassword())) abort(406);  //Password Validation
        $game = Game::where('abbreviation', $validated['game'])->with('gameConfiguration')->get();
        $config = $game->map(function ($row) {
            return $row->getRelation('gameConfiguration');
        });
        $drawPeriod = DrawPeriod::find($validated['drawPeriodId']);
        if (strtotime($drawPeriod->draw_time) > strtotime(Carbon::now()->toTimeString())) abort(406);

        // CHECK COMBINATION FORMAT
        $mCombination = explode('-', $validated['combination']);
        if ($config[0]->field_set != count($mCombination)) abort(406);
        foreach ($mCombination as $fieldSet) {
            if ($config[0]->digit_per_field_set != strlen($fieldSet)) abort(406);
        }

        $exists = WinningCombination::search(Carbon::now()->toDateString())
            ->where('draw_period_id', $drawPeriod->id)
            ->where('game_id', $game[0]->id)->first();
        if ($exists) abort(406);
        $winningCombination = WinningCombination::create([
            'combination' => $validated['combination'],
            'draw_period_id' => $drawPeriod->id,
            'game_id' => $game[0]->id,
        ]);

        return response(['winningCombination' => $winningCombination], 202);
    }


    public function update(Request $request, $winningCombination)
    {
        $this->authorize('update-winning-combinations', $winningCombination);
        $validated = $request->validate([
            'password' => 'required',
            'combination' => 'required',
            'game' => 'required',
            'draw_period_id' => 'required'
        ]);
        if (!Hash::check($validated['password'], Auth::user()->getAuthPassword())) abort(406);  //Password Validation
        $winCombination = WinningCombination::find($winningCombination);

        unset($validated['password'], $validated['game']);
        $winCombination->update($validated);
        $this->publishWinners($winCombination);

        return response([$winCombination], 200);
    }

    public function verify(Request $request, $winningCombination)
    {
        $this->authorize('update-winning-combinations', WinningCombination::class);
        $validated = $request->validate(['password' => 'required']);
        if (!Hash::check($validated['password'], Auth::user()->getAuthPassword())) abort(406);  //Password Validation
        $winningCombination = WinningCombination::find($winningCombination);

        $winningCombination->update([
            'verified_at' => Carbon::now(),
        ]);
        $this->publishWinners($winningCombination);
        return response([$winningCombination], 202);
    }


    public function destroy(Request $request, WinningCombination $winningCombination)
    {
        $this->authorize('delete-winning-combinations', $winningCombination);
        $winningCombination->delete();
        return response([], 204);
    }


    private function publishWinners(WinningCombination $winningCombination)
    {
        $deleteBets = DB::table('winning_bets')->where('winning_combination_id', $winningCombination->id)->delete();
        $insertBets = DB::table('winning_bets')
            ->insertUsing(
                ['bet_id', 'winning_combination_id', 'created_at', 'updated_at'],
                "SELECT
                        b.id as bet_id,
                        {$winningCombination->id} as winning_combination_id,
                        '{$winningCombination->created_at}' as created_at,
                        '{$winningCombination->created_at}' as updated_at
                        FROM bets b
                        WHERE b.game_id = {$winningCombination->game_id}
                        AND b.combination = {$winningCombination->combination}
                        AND b.draw_period_id = {$winningCombination->draw_period_id}
                        AND DATE(b.created_at) = DATE('{$winningCombination->created_at}')"
            );
    }
}
