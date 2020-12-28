<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\DrawPeriod;
use App\Models\Game;
use Illuminate\Http\Request;

class ApiDrawPeriodController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list draw periods', DrawPeriod::class);
        $search = $request->get('search', '');
        $drawPeriods = DrawPeriod::search($search)->with('games:description')->get();
        return response(['drawPeriods' => $drawPeriods], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create draw periods', DrawPeriod::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create draw periods', DrawPeriod::class);
        $validated = $request->validate([
            'draw_time' => 'required',
            'draw_type' => 'required',
            'games.*' => 'required',
            'open_time' => 'required',
            'close_time' => 'required'
        ]);

        $games = Game::whereIn('description', $validated['games'])->get()->map(function ($row) {
            return $row->id;
        })->toArray();

        $drawPeriod = DrawPeriod::create([
            'draw_time' => $validated['draw_time'],
            'draw_type' => $validated['draw_type'],
            'open_time' => $validated['open_time'],
            'close_time' => $validated['close_time'],
        ]);
        $drawPeriod->games()->sync($games);

        return response(['drawPeriod' => $drawPeriod], 202);
    }

    public function show(Request $request, DrawPeriod $drawPeriod)
    {
        $this->authorize('view draw periods', $drawPeriod);
        return response([], 204);
    }

    public function edit(Request $request, DrawPeriod $drawPeriod)
    {
        $this->authorize('update draw periods', $drawPeriod);

        return response(['drawPeriod' => $drawPeriod], 200);
    }

    public function update(Request $request, DrawPeriod $drawPeriod)
    {
        $this->authorize('update draw periods', $drawPeriod);
        $validated = $request->validate([
            'draw_time' => 'required',
            'draw_type' => 'required',
            'games.*' => 'required',
            'open_time' => 'required',
            'close_time' => 'required'
        ]);
        $games = Game::whereIn('description', $validated['games'])->get()->map(function ($row) {
            return $row->id;
        })->toArray();
//
        $drawPeriod->update([
            'draw_time' => $validated['draw_time'],
            'draw_type' => $validated['draw_type'],
            'open_time' => $validated['open_time'],
            'close_time' => $validated['close_time'],
        ]);
        $drawPeriod->games()->sync($games);
        return response([], 204);
    }

    public function destroy(Request $request, DrawPeriod $drawPeriod)
    {
        $this->authorize('delete draw periods', $drawPeriod);
        $drawPeriod->delete();
        return response([], 204);
    }

    public function getDrawPeriodGames(Request $request)
    {
        $this->authorize('list draw periods', DrawPeriod::class);
        $search = $request->get('search', '');
        $drawPeriods = DrawPeriod::search($search)->with('games:description')->get()
            ->mapWithKeys(function ($item) {
                return [
                    strtoupper(substr($item['draw_type'], 0, 1) . '-' . $item['draw_time']) => $item['games'],
                ];
            });

        $games = Game::search($search)->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item['description'] => $item,
                ];
            });

        return response([$games, $drawPeriods], 200);
    }
}
