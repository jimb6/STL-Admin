<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\PasswordGenerator;
use App\Helpers\TwilioSmsHelper;
use App\Models\Address;
use App\Models\Cluster;
use App\Models\User;
use App\Scopes\StatusScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAgentController extends ApiController
{

    public function index(Request $request)
    {
        $this->authorize('list-agents', User::class);
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

        $address = Address::firstOrCreate([
            'street' => $validated['address']['0'],
            'barangay' => $validated['address']['1'],
            'municipality' => $validated['address']['2'],
            'province' => $validated['address']['3'],
        ]);



        $generated_password = PasswordGenerator::random();
        unset($validated['address'], $validated['roles']);
        if ($request->has('email')) $validated['email'] = $request->get('email');
        $validated['address_id'] = $address->id;
        $validated['password'] = Hash::make($generated_password);

        $user = User::withoutGlobalScope(StatusScope::class)->firstOrCreate($validated);
        $sms = new TwilioSmsHelper('ACf07ba6ddfcf865b96b6f15c6e8e1f892', 'a65a1f4f71eca0147993a6d0314245a5', '+12059538412');
        $user->assignRole('agent');
        $smsStatus = $sms->sendSms($validated['contact_number'], $generated_password);
        return response(['user' => $user, 'sms' => $smsStatus], 202);
    }

    public function show(Request $request, $user)
    {
        $this->authorize('view-agents', User::class);
        return response(['user' => $user], 200);
    }

    public function update(Request $request, $user)
    {
        $this->authorize('update-agents', User::class);
        $validated = $request->validate([
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'contact_number' => 'required',
            'cluster_id' => 'required',
            'address.*' => 'required'
        ]);

        $address = $validated['address'];
        $address = Address::updateOrCreate(
            ['street' => $address[0], 'barangay' => $address[1], 'municipality' =>$address[2], 'province'=>$address[3]]
        );

        $validated['address_id'] = $address->id;
        $validated['birthdate'] = Carbon::parse($validated['birthdate'])->format('Y-m-d H:i:s');
        unset($validated['address']);

        $user = User::where('id', $user)->first()->update($validated);
        return response(['message' => $validated], 202);
    }

    public function destroy(Request $request, $user)
    {
        $this->authorize('delete-agents', $user);
        User::withoutGlobalScope(StatusScope::class)->where('id', $user)->first()->delete();
        return response([], 204);
    }

    public function activeIndex(Request $request)
    {
        $this->authorize('list-agents', User::class);
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
        $this->authorize('list-agents', User::class);
//        $search = $request->get('search', '');
        $agents = User::withoutGlobalScope(StatusScope::class)->with(['cluster', 'address'])
            ->whereHas('roles', function ($query) {
                $query->where('name', '=', 'agent');
            })->where('cluster_id', $cluster->id)->get();
        return response(['agents' => $agents], 200);
    }





}
