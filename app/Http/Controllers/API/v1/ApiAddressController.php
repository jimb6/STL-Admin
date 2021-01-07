<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAddressController extends ApiController
{
    public function index(Request $request)
    {
        $this->authorize('list addresses', Address::class);
        $search = $request->get('search', '');
        $address = Address::search($search)->get();
        return response(['address'=>$address], 200);
    }


    public function create(Request $request)
    {
        $this->authorize('create addresses', Address::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-addresses', Address::class);
        $validated = $request->validated();
        $address = Address::create($validated);
        return response(['message'=>'Address Created Successfully!'],201);
    }


    public function show(Request $request, Address $address)
    {
        $this->authorize('view-addresses', $address);
        return response(['address' => $address], 200);
    }


    public function edit(Request $request, Address $address)
    {
        $this->authorize('update-addresses', $address);
        return response([], 200);
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update-addresses', $address);
        $validated = $request->validated();
        $address->update($validated);
        return response(['message'=>'Address Updated Successfully!'], 202);
    }

    public function destroy(Request $request, Address $address)
    {
        $this->authorize('delete-addresses', $address);
        $address->delete();
        return response([], 204);
    }
}
