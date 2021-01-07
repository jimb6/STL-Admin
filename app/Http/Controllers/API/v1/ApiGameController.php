<?php

namespace App\Http\Controllers\API\v1;

use App\Events\GameConfigEvent;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\CloseNumber;
use App\Models\DrawPeriod;
use App\Models\Game;
use App\Models\GameConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiGameController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->can('list-games', Game::class);
        $search = $request->get('search', '');
        $games = Game::with(['drawPeriods', 'controlCombination', 'bets', 'gameConfiguration'])->get();
        return response(['games' => $games], 200);
    }

    public function create(Request $request)
    {
        $request->user()->can('create-games', Game::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $request->user()->can('create-games', Game::class);
        $validated = $request->validate([
            "description" => "required",
            "abbreviation" => "required",
            "multiplier" => "required",
            "field_set" => "required",
            "digit_per_field_set" => "required",
            "max_per_bet" => "required",
            "min_per_bet" => "required",
            "has_repetition" => "required",
            "days_availability" => "required|array",
            "is_rumbled" => "required",
            "max_sum_bet" => "required",
            "transaction_limit" => "required",
            'min_per_field_set' => "required",
            'max_per_field_set' => "required",
        ]);

        $game = Game::firstOrcreate([
            'description' => $validated['description'],
            'abbreviation' => $validated['abbreviation'],
        ]);

        $config = GameConfiguration::firstOrcreate([
            'game_id' => $game->id,
            'multiplier' => $validated['multiplier'],
            'field_set' => $validated['field_set'],
            'digit_per_field_set' => $validated['digit_per_field_set'],
            'max_per_bet' => $validated['max_per_bet'],
            'min_per_bet' => $validated['min_per_bet'],
            'has_repetition' => $validated['has_repetition'],
            'days_availability' => $validated['days_availability'],
            'is_rumbled' => $validated['is_rumbled'],
            'max_sum_bet' => $validated['max_sum_bet'],
            'transaction_limit' => $validated['transaction_limit'],
            'min_per_field_set' => $validated['min_per_field_set'],
            'max_per_field_set' => $validated['max_per_field_set'],
        ]);

        $game->gameConfiguration->associate($config);
        return response(['game' => $validated], 200);
    }

    public function show(Request $request, Game $games)
    {
        $request->user()->can('view-games', $games);
        return response([$games], 200);
    }

    public function edit(Request $request, Game $game)
    {
        $request->user()->can('update-games', $game);
        return response(['game' => $game], 200);
    }

    public function update(Request $request, Game $game)
    {
        $request->user()->can('update-games', $game);
        $validated = $request->validate([
            "description" => "required",
            "abbreviation" => "required",
            "multiplier" => "required",
            "field_set" => "required",
            "digit_per_field_set" => "required",
            "max_per_bet" => "required",
            "min_per_bet" => "required",
            "has_repetition" => "required",
            "days_availability" => "required|array",
            "is_rumbled" => "required",
            "max_sum_bet" => "required",
            "transaction_limit" => "required",
            'min_per_field_set' => "required",
            'max_per_field_set' => "required",
        ]);
        $gameConfig = GameConfiguration::where('game_id', $game->id)
            ->update([
                'multiplier' => $validated['multiplier'],
                'field_set' => $validated['field_set'],
                'digit_per_field_set' => $validated['digit_per_field_set'],
                'max_per_bet' => $validated['max_per_bet'],
                'min_per_bet' => $validated['min_per_bet'],
                'has_repetition' => $validated['has_repetition'],
                'days_availability' => $validated['days_availability'],
                'is_rumbled' => $validated['is_rumbled'],
                'max_sum_bet' => $validated['max_sum_bet'],
                'transaction_limit' => $validated['transaction_limit'],
                'min_per_field_set' => $validated['min_per_field_set'],
                'max_per_field_set' => $validated['max_per_field_set'],
            ]);
        $game->update([
            'description' => $validated['description'],
            'abbreviation' => $validated['abbreviation'],
        ]);

        return response(['game' => $validated], 202);
    }

    public function destroy(Request $request, Game $game)
    {
        $request->user()->can('delete-games', $game);
        $game->delete();
        return response([], 204);
    }

    public function configIndex(Request $request, $abbreviation)
    {
        $request->user()->can('list-games', Game::class);
        $search = $request->get('search', '');
        $game = Game::where('abbreviation', $abbreviation)
            ->with(['gameConfiguration', 'controlCombination', 'bets'])
            ->get();
        $draw = DrawPeriod::currentDraw()->whereHas('games', function ($query) use ($game) {
            $query->where('id', $game[0]->id);
        })->first();

        $bets = Bet::currentDraw()->where('game_id', $game[0]->id)->get()
            ->groupBy('combination')->map(function ($row) {
                return ['sum' => $row->sum('amount'), 'bets' => $row];
            });

        $closedNumbers = CloseNumber::all();

        return response(['bets' => $bets, 'game' => $game, 'draw_period' => $draw, 'closed_numbers' => $closedNumbers], 200);
    }

    public function configUpdate(Request $request, $game)
    {
        $request->user()->can('update-games', Game::class);
        $game = Game::where('abbreviation', $game)->first();
        $gameConfig = GameConfiguration::where('game_id', $game->id)
            ->update([$request->get('col_name') => $request->get('config')]);
        broadcast(new GameConfigEvent($game));
        return response([$gameConfig], 200);
    }

    public function configDaysUpdate(Request $request, $game)
    {
        $request->user()->can('update-games', Game::class);
        $validated = $request->validate(['days' => 'required|array']);
        $game = Game::where('abbreviation', $game)->first();
        $gameConfig = GameConfiguration::where('game_id', $game->id)
            ->update(['days_availability' => json_encode($validated['days'])]);
        return response([$gameConfig], 200);
    }

    public function configMobileIndex(Request $request)
    {
        $request->user()->can('list-games', Game::class);
        $search = $request->get('search', '');
        $currentDay = Carbon::now()->tz(config('app.timezone'))->format('l');
        $games = Game::with(['gameConfiguration'])
            ->whereHas('drawPeriods', function ($query) {
                $query->whereTime('open_time', '<', Carbon::now()->toTimeString())
                    ->whereTime('close_time', '>', Carbon::now()->toTimeString());
            })->get()
            ->reject(function ($row) use ($currentDay) {
                return !in_array($currentDay, $row['gameConfiguration']->days_availability);
            });
        return response(['games' => $games], 200);
    }

}
