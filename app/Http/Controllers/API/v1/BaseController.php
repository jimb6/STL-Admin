<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Base;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view bases', Base::class);
        $search = $request->get('search', '');
        $bases = Base::search($search)->get();
//        return view('app.bases.index', compact('bases', 'search'));
        return response(['bases' => $bases], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create bases', Base::class);

        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create bases', Base::class);
        $validated = $request->validated();
        $base = Base::create($validated);

//        return redirect()->route('bases.edit', $base);
        return response(['base' => $base], 202);
    }

    public function show(Request $request, Base $base)
    {
        $this->authorize('view bases', $base);
//        return view('app.bases.show', compact('base'));
        return response([], 204);
    }

    public function edit(Request $request, Base $base)
    {
        $this->authorize('update base', $base);

//        return view('app.bases.edit', compact('base'));
        return response(['base' => $base], 200);
    }

    public function update(Request $request, Base $base)
    {
        $this->authorize('update base', $base);
        $validated = $request->validated();
        $base->update($validated);
        return response([$base], 202);
    }

    public function destroy(Request $request, Base $base)
    {
        $this->authorize('delete', $base);
        $base->delete();
        return response(['base' => $base], 202);
    }
}
