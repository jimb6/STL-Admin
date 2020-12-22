<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiClusterController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list clusters', Cluster::class);
        $search = $request->get('search', '');
        $clusters = Cluster::search($search)->get();
        $user = Auth::check()? Auth::user():null;
        if ($user && !$user->hasRole('Super-Admin')){
            $clusters = $clusters->reject(function ($value, $key) {
                return $value['cluster_type'] == 'Main';
            });
        }
        return response(['clusters' => $clusters], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create clusters', Cluster::class);

        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create clusters', Cluster::class);
        $validated = $request->validated();
        $cluster = Cluster::create($validated);

//        return redirect()->route('clusters.edit', $cluster);
        return response(['cluster' => $cluster], 202);
    }

    public function show(Request $request, Cluster $cluster)
    {
        $this->authorize('view clusters', $cluster);
//        return view('app.clusters.show', compact('cluster'));
        return response([], 204);
    }

    public function edit(Request $request, Cluster $cluster)
    {
        $this->authorize('update clusters', $cluster);

//        return view('app.clusters.edit', compact('cluster'));
        return response(['cluster' => $cluster], 200);
    }

    public function update(Request $request, Cluster $cluster)
    {
        $this->authorize('update clusters', $cluster);
        $validated = $request->validated();
        $cluster->update($validated);
        return response([$cluster], 202);
    }

    public function destroy(Request $request, Cluster $cluster)
    {
        $this->authorize('delete clusters', $cluster);
        $cluster->delete();
        return response([], 204);
    }
}
