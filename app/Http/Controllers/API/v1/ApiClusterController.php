<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Commission;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiClusterController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->can('list-clusters', Cluster::class);
        $search = $request->get('search', '');
        $clusters = Cluster::search($search)->with('agents', 'commissions')->get();
        $user = Auth::check() ? Auth::user() : null;
//        if ($user && !$user->hasRole('super-admin')){
//            $clusters = $clusters->reject(function ($value, $key) {
//                return $value['cluster_type'] == 'Main';
//            });
//        }
        return response(['clusters' => $clusters], 200);
    }

    public function create(Request $request)
    {
        $request->user()->can('create-clusters', Cluster::class);

        return response([], 200);
    }

    public function store(Request $request)
    {
        $request->user()->can('create-clusters', Cluster::class);
        $validated = $request->validate([
            'name' => 'required',
            'cluster_type',
        ]);
        $cluster = Cluster::firstOrCreate($validated);
        $games = Game::all();
        foreach ($games as $game){
            if ($request->has($game->abbreviation)){
                Commission::updateOrCreate(
                    ['cluster_id' => $cluster->id, 'game_id' => $game->id],
                    ['commission_rate' => (intval($request->get($game->abbreviation)) * .01)]
                );
            }
        }
        return response([$cluster], 202);
    }

    public function show(Request $request, Cluster $cluster)
    {
        $request->user()->can('view-clusters', $cluster);
//        return view('app.clusters.show', compact('cluster'));
        return response([], 204);
    }

    public function edit(Request $request, Cluster $cluster)
    {
        $request->user()->can('update-clusters', $cluster);

//        return view('app.clusters.edit', compact('cluster'));
        return response(['cluster' => $cluster], 200);
    }

    public function update(Request $request, Cluster $cluster)
    {
        $request->user()->can('update-clusters', $cluster);
        $validated = $request->validate([
            'name' => 'required',
            'cluster_type',
        ]);
        $games = Game::all();
        foreach ($games as $game){
            if ($request->has($game->abbreviation)){
                Commission::updateOrCreate(
                    ['cluster_id' => $cluster->id, 'game_id' => $game->id],
                    ['commission_rate' => (intval($request->get($game->abbreviation)) * .01)]
                );
            }
        }
        $cluster->update($validated);
        return response($request->all(), 202);
    }

    public function destroy(Request $request, Cluster $cluster)
    {
        $request->user()->can('delete-clusters', $cluster);
        $cluster->delete();
        return response([], 204);
    }

    public function getClusterWithCommissions(Request $request, $game){
        $request->user()->can('list-clusters', Cluster::class);
        $game = Game::where('abbreviation', $game)->first();
        $search = $request->get('search', '');
        $clusters = Cluster::search($search)->with(['commissions' => function($query) use ($game) {
            $query->where('game_id', $game->id);
        }])->get();


        $groupedId = $clusters->mapToGroups(function ($item, $key) {
            return
                count($item['commissions']) > 0 && $item['commissions'][0]->commission_rate > 0?
                    ["with-commission" => $item['id']] :
                    ["without-commission" => $item['id']];
        })->all();
        $grouped = $clusters->mapToGroups(function ($item, $key) {
            return
                count($item['commissions']) > 0 && $item['commissions'][0]->commission_rate > 0?
                ["with-commission" => $item] :
                ["without-commission" => $item];
        })->all();

        return response(['clusters' => $grouped, 'clustersId' => $groupedId], 200);
    }
}
