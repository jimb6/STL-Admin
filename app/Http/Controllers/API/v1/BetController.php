<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use Illuminate\Http\Request;

class BetController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view bets', Bet::class);
        $search = $request->get('search', '');
        $bets = Bet::search($search)->with(['game', 'drawPeriod'])
            ->withCasts(['is_voided' => 'boolean', 'is_rumbled' => 'boolean'])->get();
        return $request->wantsJson() ? response(['bets' => $bets], 200) :
            view('bets.index');
    }

    public function create(Request $request)
    {
        $this->authorize('create bets', Bet::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create bets', Bet::class);
        $validated = $request->validated();
        $bet = Bet::create($validated);

        return response(['bet' => $bet], 202);
    }

    public function show(Request $request, Bet $bet)
    {
        $this->authorize('view bets', $bet);
        return response([], 204);
    }

    public function edit(Request $request, Bet $bet)
    {
        $this->authorize('update bets', $bet);
        return response(['bet' => $bet], 200);
    }

    public function update(Request $request, Bet $bet)
    {
        $this->authorize('update bets', $bet);
        $validated = $request->validated();
        $bet->update($validated);
        return response([$bet], 202);
    }

    public function destroy(Request $request, Bet $bet)
    {
        $this->authorize('delete bets', $bet);
        $bet->delete();
        return response([], 204);
    }

    public function topGames(Request $request){
        $this->authorize('view bets', Bet::class);
        $search = $request->get('search', '');
        $bets = Bet::search($search)->with(['game', 'drawPeriod'])
            ->withCasts(['is_voided' => 'boolean', 'is_rumbled' => 'boolean'])
            ->groupBy('')->get();

        return $request->wantsJson() ? response(['bets' => $bets], 200) :
            view('bets.index');
    }
}
