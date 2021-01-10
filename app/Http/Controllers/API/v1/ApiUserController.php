<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\PasswordGenerator;
use App\Helpers\TwilioSmsHelper;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cluster;
use App\Models\DrawPeriod;
use App\Models\User;
use App\Scopes\StatusScope;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class ApiUserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-users', User::class);
        $search = $request->get('search', '');
        $users = User::withoutGlobalScope(StatusScope::class)->search($search)->with(['cluster', 'address', 'roles'])->get();
//
        return response(['users' => $users], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create-users', User::class);
        $clusters = Cluster::pluck('name', 'id');
        $roles = Role::get();
        return response(['clusters' => $clusters, 'roles' => $roles], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-users', User::class);
        $validated = $request->validate([
            'roles' => 'required|array',
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'contact_number' => 'required',
            'cluster_id' => 'required',
            'address.*' => 'required'
        ]);

        $address = Address::firstOrCreate([
            'street' => $validated['address']['0'],
            'barangay' => $validated['address']['1'],
            'municipality' => $validated['address']['2'],
            'province' => $validated['address']['3'],
        ]);

        $generated_password = PasswordGenerator::random();
        $roles = $validated['roles'];
        unset($validated['address'], $validated['roles']);
        if ($request->has('email')) $validated['email'] = $request->get('email');
        $validated['address_id'] = $address->id;
        $validated['password'] = Hash::make($generated_password);

        $user = User::create($validated);
        $sms = new TwilioSmsHelper('ACf07ba6ddfcf865b96b6f15c6e8e1f892', 'a65a1f4f71eca0147993a6d0314245a5', '+12059538412');
        $sms->sendSms($validated['contact_number'], $generated_password);
        $user->assignRole($roles);
        return response(['user' => $user], 202);
    }

    public function show(Request $request, $user)
    {
        $this->authorize('view-users', User::class);
        $user = User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first();
        return response(['user' => $user], 200);
//        return view('app.users.show', compact('user'));
    }

    public function edit(Request $request, $user)
    {
        $this->authorize('update-users', User::class);
        $bases = Base::pluck('base_name', 'id');
        $roles = Role::get();
        return response(['bases' => $bases, 'roles' => $roles], 200);
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
        $user = User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first();
        $user->update($validated);
        $user->syncRoles($request->roles);
        return response(['message' => 'User Updated Successfully!'], 202);
    }

    public function destroy(Request $request, $user)
    {
        $this->authorize('delete-users', User::class);
        $user = User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first();
        $user->delete();
        return response([], 204);
    }


    public function baseRoleIndex(Request $request, $role)
    {
        $this->authorize('list-users', User::class);
        $search = $request->get('search', '');
        $users = User::withoutGlobalScope(StatusScope::class)->search($search)->with(['cluster', 'address', 'roles' => function ($query) use ($role) {
            $query->whereIn('name', [$role]);
        }])->get()
            ->reject(function ($value, $key) {
                return $value['roles']->isEmpty();
            });
        return response(['users' => $users], 200);
    }


    public function deactivateUser(Request $request, $id){
        $this->authorize('update-users', User::class);
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        if ($request->user()->id == $id) abort(406);

        User::withoutGlobalScope(StatusScope::class)
            ->where('id', $id)
            ->first()
            ->update($validated);

        return response([$validated], 200);
    }

}
