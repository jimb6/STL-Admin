<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\CloseNumber;
use Illuminate\Http\Request;

class ApiCloseNumberController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list close numbers', CloseNumber::class);
        $search = $request->get('search', '');
        $closeNumbers = CloseNumber::with(['game', 'drawPeriod'])->get();
        return response(['closeNumbers' => $closeNumbers], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create close numbers', CloseNumber::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create close numbers', CloseNumber::class);
        $validated = $request->validated();
        $closeNumber = CloseNumber::create($validated);
        return response(['closeNumber' => $closeNumber], 202);
    }

    public function show(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('view close numbers', CloseNumber::class);
        return response([], 204);
    }

    public function edit(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('update close numbers', $closeNumber);
        return response(['closeNumbers' => $closeNumber], 200);
    }

    public function update(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('update close numbers', $closeNumber);
        $validated = $request->validated();
        $closeNumber->update($validated);
        return response([$closeNumber], 202);
    }

    public function destroy(Request $request, CloseNumber $closeNumber)
    {
        $this->authorize('delete close numbers', $closeNumber);
        $closeNumber->delete();
        return response([], 204);
    }

}
