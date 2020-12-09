<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\Booth;
use Illuminate\Http\Request;

class BoothController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view booths', Booth::class);

        $search = $request->get('search', '');

        $booths = Booth::search($search)
            ->latest()
            ->paginate();

        return view('booths.index', compact('booths', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create booths', Booth::class);

        $bases = Base::pluck('base_name', 'id');

        return view('booths.create', compact('bases'));
    }

    /**
     * @param \App\Http\Requests\BoothStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoothStoreRequest $request)
    {
        $this->authorize('create booths', Booth::class);

        $validated = $request->validated();

        $booth = Booth::create($validated);

        return redirect()->route('booths.edit', $booth);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Booth $booth)
    {
        $this->authorize('view booths', $booth);

        return view('app.booths.show', compact('booth'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Booth $booth)
    {
        $this->authorize('update booths', $booth);

        $bases = Base::pluck('base_name', 'id');

        return view('app.booths.edit', compact('booth', 'bases'));
    }

    /**
     * @param \App\Http\Requests\BoothUpdateRequest $request
     * @param \App\Models\Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function update(BoothUpdateRequest $request, Booth $booth)
    {
        $this->authorize('update booths', $booth);

        $validated = $request->validated();

        $booth->update($validated);

        return redirect()->route('booths.edit', $booth);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Booth $booth)
    {
        $this->authorize('delete booths', $booth);

        $booth->delete();

        return redirect()->route('booths.index');
    }


    public function getActiveBooths()
    {
        $allBooths = Booth::all()->where('base_id', '=', 1);
        $activeBooths = $allBooths->
        where('status', '=', "1")->count();

        return response(['total' => count($allBooths), 'active' => $activeBooths], 200)
            ->header('Content-Type', 'application/json');
    }
}
