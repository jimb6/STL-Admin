<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAddressController extends ApiController
{
    public function index(Request $request)
    {
        Auth::user()->can('list addresses', Address::class);
        $search = $request->get('search', '');
        $address = Address::search($search)->get();
        return response(['address'=>$address], 200);
    }


    public function create(Request $request)
    {
        Auth::user()->can('create addresses', Address::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        Auth::user()->can('create-addresses', Address::class);
        $validated = $request->validated();
        $address = Address::create($validated);
        return response(['message'=>'Address Created Successfully!'],201);
    }


    public function show(Request $request, Address $address)
    {
        Auth::user()->can('view-addresses', $address);
        return response(['address' => $address], 200);
    }


    public function edit(Request $request, Address $address)
    {
        Auth::user()->can('update-addresses', $address);
        return response([], 200);
    }

    public function update(Request $request, Address $address)
    {
        Auth::user()->can('update-addresses', $address);
        $validated = $request->validated();
        $address->update($validated);
        return response(['message'=>'Address Updated Successfully!'], 202);
    }

    public function destroy(Request $request, Address $address)
    {
        Auth::user()->can('delete-addresses', $address);
        $address->delete();
        return response([], 204);
    }
}
