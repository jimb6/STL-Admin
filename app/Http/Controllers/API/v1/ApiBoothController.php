<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBoothController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('list-booths', Booth::class);
        $search = $request->get('search', '');
        $booths = Booth::search($search)->get();
        return \response(['address'=>$booths], 200);
    }


    public function create(Request $request)
    {
        $this->authorize('create-booths', Booth::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-booths', Booth::class);
        $validated = $request->validated();
        $booth = Booth::create($validated);
        return \response(['message'=>'Booth Created Successfully!'],201);
    }


    public function show(Request $request, Booth $booth)
    {
        $this->authorize('view-booths', $booth);
        return \response(['booth' => $booth], 200);
    }


    public function edit(Request $request, Booth $booth)
    {
        $this->authorize('update-booths', $booth);
        return \response([], 200);
    }

    public function update(Request $request, Booth $booth)
    {
        $this->authorize('update-booths', $booth);
        $validated = $request->validated();
        $booth->update($validated);
        return \response(['message'=>'Booth Updated Successfully!'], 202);
    }

    public function destroy(Request $request, Booth $booth)
    {
        $this->authorize('delete-addresses', $booth);
        $booth->delete();
        return \response([], 204);
    }
}
