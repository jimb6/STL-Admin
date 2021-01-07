<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCommissionController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->can('list-commissions', Commission::class);
        $search = $request->get('search', '');
        $commissions = Commission::search($search)->with('agents')->get();
        $user = Auth::check()? Auth::user():null;
        if ($user && !$user->hasRole('Super-Admin')){
            $commissions = $commissions->reject(function ($value, $key) {
                return $value['cluster_type'] == 'Main';
            });
        }
        return response(['clusters' => $commissions], 200);
    }

    public function store(Request $request)
    {
        $request->user()->can('create-commissions', Commission::class);
        $validated = $request->validated();
        $commission = Commission::create($validated);

//        return redirect()->route('clusters.edit', $commission);
        return response(['cluster' => $commission], 202);
    }

    public function show(Request $request, Commission $commission)
    {
        $request->user()->can('view-commissions', $commission);
//        return view('app.clusters.show', compact('cluster'));
        return response([], 204);
    }

    public function edit(Request $request, Commission $commission)
    {
        $request->user()->can('update-commissions', $commission);

//        return view('app.clusters.edit', compact('cluster'));
        return response(['cluster' => $commission], 200);
    }

    public function update(Request $request, Commission $commission)
    {
        $request->user()->can('update-commissions', $commission);
        $validated = $request->validated();
        $commission->update($validated);
        return response([$commission], 202);
    }

    public function destroy(Request $request, Commission $commission)
    {
        $request->user()->can('delete-commissions', $commission);
        $commission->delete();
        return response([], 204);
    }
}
