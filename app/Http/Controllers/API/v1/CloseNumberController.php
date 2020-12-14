<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\CloseNumber;
use Illuminate\Http\Request;

class CloseNumberController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', CloseNumber::class);

        $search = $request->get('search', '');

        $closeNumbers = CloseNumber::search($search)
            ->latest()
            ->paginate();

        return view(
            'app.close_numbers.index',
            compact('closeNumbers', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', CloseNumber::class);

        $betGames = Game::pluck('game_name', 'id');

        return view('app.close_numbers.create', compact('betGames'));
    }

    /**
     * @param \App\Http\Requests\CloseNumberStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CloseNumberStoreRequest $request)
    {
        $this->authorize('create', CloseNumber::class);

        $validated = $request->validated();

        $closeNumber = CloseNumber::create($validated);

        return redirect()->route('close-numbers.edit', $closeNumber);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CloseNumber $closeNumber
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('view', $closeNumber);

        return view('app.close_numbers.show', compact('closeNumber'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CloseNumber $closeNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('update', $closeNumber);

        $betGames = Game::pluck('game_name', 'id');

        return view(
            'app.close_numbers.edit',
            compact('closeNumber', 'betGames')
        );
    }

    /**
     * @param \App\Http\Requests\CloseNumberUpdateRequest $request
     * @param \App\Models\CloseNumber $closeNumber
     * @return \Illuminate\Http\Response
     */
    public function update(
        CloseNumberUpdateRequest $request,
        CloseNumber $closeNumber
    ) {
        $this->authorize('update', $closeNumber);

        $validated = $request->validated();

        $closeNumber->update($validated);

        return redirect()->route('close-numbers.edit', $closeNumber);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CloseNumber $closeNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('delete', $closeNumber);

        $closeNumber->delete();

        return redirect()->route('close-numbers.index');
    }
}
