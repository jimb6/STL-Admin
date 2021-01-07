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
        $this->authorize('list-roles', Role::class);
        $search = $request->get('search', '');
        $roles = Role::with('permissions')->get();
        return response(['roles' => $roles], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create-roles', Role::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-roles', Role::class);
        $validated = $request->validated();
        $role = Role::create($validated);

        return response(['permission' => $role], 202);
    }

    public function show(Request $request, Role $role)
    {
        $this->authorize('view-roles', $role);
        return response([], 204);
    }

    public function edit(Request $request, Role $role)
    {
        $this->authorize('update-roles', $role);
        return response(['permission' => $role], 200);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update-roles', $role);
        $validated = $request->validated();
        $role->update($validated);
        return response([$role], 202);
    }

    public function destroy(Request $request, Role $role)
    {
        $this->authorize('delete-roles', $role);
        $role->delete();
        return response([], 204);
    }
}
