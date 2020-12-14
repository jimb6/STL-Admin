<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\Booth;
use App\Models\Cluster;
use Illuminate\Http\Request;

class BoothController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view booths', Booth::class);
        $search = $request->get('search', '');
        $booths = Booth::search($search)->get();
//       return view('booths.index', compact('booths', 'search'));
        return $request->wantsJson() ?
            response(['booths' => $booths, 'search' => $search]) :
            view('booths.index', compact('booths', 'search'));
    }


    public function create(Request $request)
    {
        $this->authorize('create booths', Booth::class);
        $clusters = Cluster::pluck('name', 'id');
        return $request->wantsJson() ?
            response(['clusters' => $clusters], 200) :
            view('booths.create', compact('clusters'));
    }

    public function store(Request $request)
    {
        $this->authorize('create booths', Booth::class);
        $validated = $request->validated();
        $booth = Booth::create($validated);
        return $request->wantsJson() ?
            response(['booths'], 202) :
            redirect()->route('booths.edit', $booth);
    }

    public function show(Request $request, Booth $booth)
    {
        $this->authorize('view booths', $booth);
        return $request->wantsJson() ?
            response(['booth' => $booth], 200) :
            view('app.booths.show', compact('booth'));
    }

    public function edit(Request $request, Booth $booth)
    {
        $this->authorize('update booths', $booth);
        $clusters = Base::pluck('base_name', 'id');
        return $request->wantsJson() ?
            response(['clusters' => $clusters, 'booth' => $booth], 200) :
            view('app.booths.edit', compact('booth', 'clusters'));
    }

    public function update(Request $request, Booth $booth)
    {
        $this->authorize('update booths', $booth);
        $validated = $request->validated();
        $booth->update($validated);
        return $request->wantsJson() ?
            response(['booth' => $booth], 202) :
            redirect()->route('booths.edit', $booth);
    }

    public function destroy(Request $request, Booth $booth)
    {
        $this->authorize('delete booths', $booth);
        $booth->delete();
        return $request->wantsJson() ?
            response([], 204) :
            redirect()->route('booths.index');
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
