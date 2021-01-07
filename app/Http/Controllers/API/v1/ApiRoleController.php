<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ApiRoleController extends Controller
{
    public function index(Request $request)
    {
        Auth::user()->can('list-roles', Role::class);
        $search = $request->get('search', '');
        $roles = Role::with('permissions')->get();
        return response(['roles' => $roles], 200);
    }

    public function create(Request $request)
    {
        Auth::user()->can('create-roles', Role::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        Auth::user()->can('create-roles', Role::class);
        $validated = $request->validated();
        $role = Role::create($validated);

        return response(['permission' => $role], 202);
    }

    public function show(Request $request, Role $role)
    {
        Auth::user()->can('view-roles', $role);
        return response([], 204);
    }

    public function edit(Request $request, Role $role)
    {
        Auth::user()->can('update-roles', $role);
        return response(['permission' => $role], 200);
    }

    public function update(Request $request, Role $role)
    {
        Auth::user()->can('update-roles', $role);
        $validated = $request->validated();
        $role->update($validated);
        return response([$role], 202);
    }

    public function destroy(Request $request, Role $role)
    {
        Auth::user()->can('delete-roles', $role);
        $role->delete();
        return response([], 204);
    }
}
