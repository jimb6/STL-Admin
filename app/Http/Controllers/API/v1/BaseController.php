<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Base;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Base::class);

        $search = $request->get('search', '');

        $bases = Base::search($search)
            ->latest()
            ->paginate();

        return view('app.bases.index', compact('bases', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Base::class);

        return view('app.bases.create');
    }

    /**
     * @param \App\Http\Requests\BaseStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BaseStoreRequest $request)
    {
        $this->authorize('create', Base::class);

        $validated = $request->validated();

        $base = Base::create($validated);

        return redirect()->route('bases.edit', $base);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Base $base
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Base $base)
    {
        $this->authorize('view', $base);

        return view('app.bases.show', compact('base'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Base $base
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Base $base)
    {
        $this->authorize('update', $base);

        return view('app.bases.edit', compact('base'));
    }

    /**
     * @param \App\Http\Requests\BaseUpdateRequest $request
     * @param \App\Models\Base $base
     * @return \Illuminate\Http\Response
     */
    public function update(BaseUpdateRequest $request, Base $base)
    {
        $this->authorize('update', $base);

        $validated = $request->validated();

        $base->update($validated);

        return redirect()->route('bases.edit', $base);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Base $base
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Base $base)
    {
        $this->authorize('delete', $base);

        $base->delete();

        return redirect()->route('bases.index');
    }
}
