<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\PasswordGenerator;
use App\Models\Address;
use App\Models\Cluster;
use App\Models\User;
use App\Scopes\StatusScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAgentController extends ApiController
{

    public function index(Request $request)
    {
        $this->authorize('list-users', User::class);
//        $search = $request->get('search', '');
        $agents = User::withoutGlobalScope(StatusScope::class)->with(['cluster', 'address'])
            ->whereHas('roles', function ($query) {
                $query->where('name', '=', 'agent');
            })->get();
        return response(['agents' => $agents], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-users', User::class);
        $validated = $request->validate([
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'contact_number' => 'required',
            'cluster_id' => 'required',
            'address.*' => 'required'
        ]);

        $this->authorize('create-agents', User::class);
        $address = Address::firstOrCreate([
            'street' => $validated['address']['0'],
            'barangay' => $validated['address']['1'],
            'municipality' => $validated['address']['2'],
            'province' => $validated['address']['3'],
        ]);

        $generated_password = substr(str_shuffle(str_repeat(PasswordGenerator::random(), 5)), 0, 8);
        $user = User::withoutGlobalScope(StatusScope::class)->firstOrCreate([
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'contact_number' => $validated['contact_number'],
            'email' => $request->has('email') ? $validated['email'] : '',
            'password' => Hash::make($generated_password),
            'cluster_id' => $validated['cluster_id'],
            'address_id' => $address->id
        ]);

        $user->assignRole('agent');
        return response(['user' => $user, 'password' => $generated_password], 202);
    }

    public function show(Request $request, $user)
    {
        $this->authorize('view-users', User::class);
        return response(['user' => $user], 200);
    }

    public function update(Request $request, $user)
    {
        $this->authorize('update-users', User::class);
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user = User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first()->update($validated);
        $user->syncRoles($request->roles);
        return response(['message' => 'User Updated Successfully!'], 202);
    }

    public function destroy(Request $request, $user)
    {
        $this->authorize('delete-users', $user);
        User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first()->delete();
        return response([], 204);
    }

    public function activeIndex(Request $request)
    {
        $this->authorize('list-users', User::class);
        $agents = User::withoutGlobalScope(StatusScope::class)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'agent');
        })->where('session_status', true)->with(['device'])->get();

        $totalAgents = User::withoutGlobalScope(StatusScope::class)->with(['user' => function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', '=', 'agent');
            });
        }])->count();

        return response(['agents' => $agents, 'total' => $totalAgents], 200);
    }

    public function agentPerCluster(Request $request, Cluster $cluster)
    {
        $this->authorize('list-users', User::class);
//        $search = $request->get('search', '');
        $agents = User::withoutGlobalScope(StatusScope::class)->with(['cluster', 'address'])
            ->whereHas('roles', function ($query) {
                $query->where('name', '=', 'agent');
            })->where('cluster_id', $cluster->id)->get();
        return response(['agents' => $agents], 200);
    }





}
