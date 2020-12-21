<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NewDeviceAdded;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ApiDeviceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list devices', Device::class);
        $search = $request->get('search', '');
        $devices = Device::search($search)->get();
        return response(['devices' => $devices], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create devices', Device::class);

        $url = URL::temporarySignedRoute(
            'device.subscribe', now()->addMinutes(30), ['cluster_id' => Auth::user()->cluster_id]
        );

        $customRequest = Request::create($url);
        $data = [
            'created_by' => \Auth::user()->id,
            'token' => $customRequest->get('signature')
        ];
        DB::table('device_registration')->where(['created_by' => Auth::user()->id])->delete();
        DB::table('device_registration')->insertOrIgnore($data);
        return response($url, 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create devices', Device::class);
        $validated = $request->validated();
        $device = Device::create($validated);
        broadcast(new NewDeviceAdded($device));

        return response(['device' => $device], 202);
    }

    public function show(Request $request, Device $device)
    {
        $this->authorize('view devices', $device);
        return response([], 204);
    }

    public function edit(Request $request, Device $device)
    {
        $this->authorize('update devices', $device);

        return response(['device' => $device], 200);
    }

    public function update(Request $request, Device $device)
    {
        $this->authorize('update devices', $device);
        $validated = $request->validated();
        $device->update($validated);
        return response([$device], 202);
    }

    public function destroy(Request $request, Device $device)
    {
        $this->authorize('delete devices', $device);
        $device->delete();
        return response([], 204);
    }

    public function subscribe(Request $request, $cluster_id)
    {
        if (!$request->hasValidSignature()) abort(401);
        $validated = $request->validate([
            'yyy' => 'required',
        ]);

        $isUsed = DB::table('device_registration')
            ->where('token', '=', $request->get('signature'))->count();
        if (!$isUsed) return response([], 403);
        else {
            DB::table('device_registration')->where(['token' => $request->get('signature')])->delete();
            $device = Device::create([
                'serial_number' => $validated['yyy'],
                'cluster_id' => $cluster_id
            ]);
//            broadcast(new NewDeviceAdded($device));
            NewDeviceAdded::dispatch($device);
            return response([], 202);
        }
    }

    public function unsubscribe(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $validated = $request->validate([
            'device_serial_number' => 'required',
        ]);
        return response($validated);
    }

}