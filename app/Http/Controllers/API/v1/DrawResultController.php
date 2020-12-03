<?php

namespace App\Http\Controllers;

use App\Models\BetGame;
use App\Models\DrawResult;
use Illuminate\Http\Request;
use App\Http\Requests\DrawResultStoreRequest;
use App\Http\Requests\DrawResultUpdateRequest;

class DrawResultController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', DrawResult::class);

        $search = $request->get('search', '');

        $drawResults = DrawResult::search($search)
            ->latest()
            ->paginate();

        return view('app.draw_results.index', compact('drawResults', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', DrawResult::class);

        $betGames = BetGame::pluck('game_name', 'id');

        return view('app.draw_results.create', compact('betGames'));
    }

    /**
     * @param \App\Http\Requests\DrawResultStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(DrawResultStoreRequest $request)
    {
        $this->authorize('create', DrawResult::class);

        $validated = $request->validated();

        $drawResult = DrawResult::create($validated);

        return redirect()->route('draw-results.edit', $drawResult);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\DrawResult $drawResult
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, DrawResult $drawResult)
    {
        $this->authorize('view', $drawResult);

        return view('app.draw_results.show', compact('drawResult'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\DrawResult $drawResult
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, DrawResult $drawResult)
    {
        $this->authorize('update', $drawResult);

        $betGames = BetGame::pluck('game_name', 'id');

        return view('app.draw_results.edit', compact('drawResult', 'betGames'));
    }

    /**
     * @param \App\Http\Requests\DrawResultUpdateRequest $request
     * @param \App\Models\DrawResult $drawResult
     * @return \Illuminate\Http\Response
     */
    public function update(
        DrawResultUpdateRequest $request,
        DrawResult $drawResult
    ) {
        $this->authorize('update', $drawResult);

        $validated = $request->validated();

        $drawResult->update($validated);

        return redirect()->route('draw-results.edit', $drawResult);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\DrawResult $drawResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DrawResult $drawResult)
    {
        $this->authorize('delete', $drawResult);

        $drawResult->delete();

        return redirect()->route('draw-results.index');
    }
}
