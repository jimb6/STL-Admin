<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view devices', Device::class);
        $search = $request->get('search', '');
        $devices = Device::search($search)->with(['user'])->get();
        return $request->wantsJson() ? response(['devices' => $devices], 200) :
            view('devices.index');
    }

    public function create(Request $request)
    {
        $this->authorize('create devices', Device::class);

        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create devices', Device::class);
        $validated = $request->validated();
        $device = Device::create($validated);

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
}
