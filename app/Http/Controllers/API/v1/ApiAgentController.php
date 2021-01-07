<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Address;
use App\Models\Agent;
use App\Models\Cluster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rainwater\Active\Active;
use Spatie\Permission\Models\Role;

class ApiAgentController extends ApiController
{

    public function index(Request $request)
    {
        $request->user()->can('list-users', Agent::class);
//        $search = $request->get('search', '');
        $agents = Agent::with(['cluster', 'address'])->get();
        return response(['agents' => $agents], 200);
    }

    public function store(Request $request)
    {
        $request->user()->can('create-users', User::class);
        $validated = $request->validate([
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'contact_number' => 'required',
            'email' => 'email',
            'cluster_id' => 'required',
            'address.*' => 'required'
        ]);

        $request->user()->can('create-agents', User::class);
        $address = Address::firstOrCreate([
            'street' => $validated['address']['0'],
            'barangay' => $validated['address']['1'],
            'municipality' => $validated['address']['2'],
            'province' => $validated['address']['3'],
        ]);

        $generated_password = substr(str_shuffle(str_repeat(config('app.key'), 5)), 0, 8);
        $user = User::firstOrCreate([
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'password' => Hash::make($generated_password),
            'cluster_id' => $validated['cluster_id'],
            'address_id' => $address->id
        ]);

        $user->assignRole('agent');
        return response(['user' => $user, 'password' => $generated_password], 202);
    }

    public function show(Request $request, User $user)
    {
        $request->user()->can('view-users', $user);
        return response(['user' => $user], 200);
    }

    public function update(Request $request, User $user)
    {
        $request->user()->can('update-users', $user);
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user->update($validated);
        $user->syncRoles($request->roles);
        return response(['message' => 'User Updated Successfully!'], 202);
    }

    public function destroy(Request $request, User $user)
    {
        $request->user()->can('delete-users', $user);
        $user->delete();
        return response([], 204);
    }

    public function activeIndex(Request $request){
        $request->user()->can('list-agents', User::class);
        $agents = User::whereHas('roles', function ($query){
            $query->where('name', '=', 'Agent');
        })->where('session_status', true)->with('device')->get();

        $totalAgents = User::with(['user' => function($query){
            $query->whereHas('roles',  function ($query) {
                $query->where('name', '=', 'Agent');
            });
        }])->count();

        return response(['agents' => $agents, 'total' => $totalAgents], 200);
    }

    public function agentPerCluster(Request $request, Cluster $cluster)
    {
        $request->user()->can('list-users', Agent::class);
//        $search = $request->get('search', '');
        $agents = Agent::with(['cluster', 'address'])->where('cluster_id', $cluster->id)->get();
        return response(['agents' => $agents], 200);
    }

}
