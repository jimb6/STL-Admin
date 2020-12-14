<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cluster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('list users', User::class);
        $search = $request->get('search', '');
        $users = User::search($search)->with(['cluster', 'address', 'roles'])->get();
        return $request->wantsJson() ? response(['users' => $users], 200)->header('Content-type', 'application/json')
            : view('users.index');
    }


    public function create(Request $request)
    {
        $this->authorize('create users', User::class);
        $clusters = Cluster::pluck('name', 'id');
        $roles = Role::get();
        return \response(['clusters' => $clusters, 'roles' => $roles], 200);
    }


    public function store(Request $request)
    {
        $this->authorize('create users', User::class);
        $validated = $request->validate([
            'roles.*' => 'required',
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'contact_number' => 'required',
            'email' => 'email',
            'cluster_id' => 'required',
            'address.*' => 'required'
        ]);

        $address = Address::firstOrCreate([
            'street' => $validated['address']['0'],
            'barangay' => $validated['address']['1'],
            'municipality' => $validated['address']['2'],
            'province' => $validated['address']['3'],
        ]);
        $user = User::firstOrCreate([
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'cluster_id' => $validated['cluster_id'],
            'address_id' => $address->id
        ]);

        $user->syncRoles(array_column($validated['roles'], 'id'));
        return response($user, 202);
    }


    public function show(Request $request, User $user)
    {
        $this->authorize('view users', $user);
        return \response(['user' => $user], 200);
//        return view('app.users.show', compact('user'));
    }


    public function edit(Request $request, User $user)
    {
        $this->authorize('update users', $user);
        $bases = Base::pluck('base_name', 'id');
        $roles = Role::get();
        return \response(['bases' => $bases, 'roles' => $roles], 200);
    }


    public function update(Request $request, User $user)
    {
        $this->authorize('update users', $user);
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user->update($validated);
        $user->syncRoles($request->roles);
        return \response(['message' => 'User Updated Successfully!'], 202);
//        return redirect()->route('users.edit', $user);
    }


    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete users', $user);
        $user->delete();
        return \response([], 204);
//        return redirect()->route('users.index');
    }
}
