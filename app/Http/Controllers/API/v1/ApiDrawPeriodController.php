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
            'draw_time' => 'required|date_format:H:i',
            'draw_type' => 'required'
        ]);
        $drawPeriod = DrawPeriod::create($validated);
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
        $validated = $request->validated();
        $drawPeriod->update($validated);
        return response([$drawPeriod], 202);
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
