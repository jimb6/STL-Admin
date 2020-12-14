<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\BetTransaction;
use Illuminate\Http\Request;

class BetTransactionController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('list bet transactions', BetTransaction::class);
        $search = $request->get('search', '');
        $betTransactions = BetTransaction::search($search)->get();
        return response(['betTransactions' => $betTransactions], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create bet transactions', BetTransaction::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create bet transactions', BetTransaction::class);
        $validated = $request->validated();
        $betTransaction = BetTransaction::create($validated);

//        return redirect()->route('clusters.edit', $cluster);
        return response(['betTransaction' => $betTransaction], 202);
    }

    public function show(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('view bet transactions', BetTransaction::class);
        return response([], 204);
    }

    public function edit(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update bet transactions', $betTransaction);
        return response(['betTransactions' => $betTransaction], 200);
    }

    public function update(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('update bet transactions', $betTransaction);
        $validated = $request->validated();
        $betTransaction->update($validated);
        return response([$betTransaction], 202);
    }

    public function destroy(Request $request, BetTransaction $betTransaction)
    {
        $this->authorize('delete bet transactions', $betTransaction);
        $betTransaction->delete();
        return response([], 204);
    }
}
