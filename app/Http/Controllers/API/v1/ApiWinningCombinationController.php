<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BetTransaction;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\WinningBet;
use App\Models\WinningCombination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $validated['dates'][1] .= ' 23:59:59';
        $winningCombinations = WinningCombination::search($search)
            ->where('game_id', $game->id)
            ->whereBetween('created_at', $validated['dates'])
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

        $winCombination = WinningCombination::find($winningCombination);
        $this->publishWinners($winCombination);
        unset($validated['password'], $validated['game']);
        $winCombination->update($validated);
        return response([$winCombination], 200);
    }

    public function verify(Request $request, $winningCombination)
    {
        $this->authorize('update-winning-combinations', WinningCombination::class);
        $validated = $request->validate(['password' => 'required']);
        if (!Hash::check($validated['password'], Auth::user()->getAuthPassword())) abort(406);  //Password Validation
        $winningCombination = WinningCombination::find($winningCombination);

        $this->publishWinners($winningCombination);
        $winningCombination->update([
            'verified_at' => Carbon::now(),
        ]);
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
        $transactions = BetTransaction::whereHas('bets', function ($query) use ($winningCombination) {
            $query->where('combination', $winningCombination->combination)
                ->whereDate('created_at', Carbon::parse($winningCombination->created_at)->format('Y-m-d'));
        })->get()->map(function ($item) use ($winningCombination) {
            return ['transaction_code' => $item->qr_code, 'winning_combination_id' => $winningCombination->id];
        })->toArray();

        $generatedWinner = WinningBet::select('id')
            ->where('winning_combination_id', $winningCombination->id)
            ->get()->toArray();

        WinningBet::destroy($generatedWinner);
        WinningBet::insert($transactions);
    }
}
