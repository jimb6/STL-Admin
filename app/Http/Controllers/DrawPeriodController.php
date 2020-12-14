<?php

namespace App\Http\Controllers;

use App\Models\DrawPeriod;
use Illuminate\Http\Request;

class DrawPeriodController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view draw periods', DrawPeriod::class);
        $search = $request->get('search', '');
        $drawPeriods = DrawPeriod::search($search)->get();
        return $request->expectsJson() ? response(['drawPeriods' => $drawPeriods], 200) :
            view('draw_periods.index');
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

        return response(['drawPeriod' => $validated], 202);
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
}
