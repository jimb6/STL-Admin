<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NewDeviceAdded;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Bet;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Rainwater\Active\Active;

class ApiBetController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'auth:sanctum']);
    }

    public function index(Request $request)
    {
        $this->authorize('list bets', User::class);
        $search = $request->get('search', '');
        $bets = Bet::with(['drawPeriod', 'game'])->get();
        return response(['bets' => $bets], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create users', User::class);
        $validated = $request->validate([
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
        return response($user, 202);
    }

    public function show(Request $request, User $user)
    {
        $this->authorize('view users', $user);
        return response(['user' => $user], 200);
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
        return response(['message' => 'User Updated Successfully!'], 202);
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete users', $user);
        $user->delete();
        return response([], 204);
    }

    public function activeIndex(){
        $this->authorize('list agents', User::class);
        $agents = Active::usersWithinHours(1)->with(['user' => function($query){
            $query->whereHas('roles',  function ($query) {
                $query->where('name', 'agent');
            });
        }])->get();
        return response(['agents' => $agents], 200);
    }
}
