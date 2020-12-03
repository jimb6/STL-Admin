<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\Booth;
use Illuminate\Http\Request;
use App\Http\Requests\BoothStoreRequest;
use App\Http\Requests\BoothUpdateRequest;

class BoothController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Booth::class);

        $search = $request->get('search', '');

        $booths = Booth::search($search)
            ->latest()
            ->paginate();

        return view('app.booths.index', compact('booths', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Booth::class);

        $bases = Base::pluck('base_name', 'id');

        return view('app.booths.create', compact('bases'));
    }

    /**
     * @param \App\Http\Requests\BoothStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoothStoreRequest $request)
    {
        $this->authorize('create', Booth::class);

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
        $this->authorize('view', $booth);

        return view('app.booths.show', compact('booth'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Booth $booth)
    {
        $this->authorize('update', $booth);

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
        $this->authorize('update', $booth);

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
        $this->authorize('delete', $booth);

        $booth->delete();

        return redirect()->route('booths.index');
    }
}
