<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApiPermissionController extends Controller
{


    public function index(Request $request)
    {
        $this->authorize('list-permissions', Permission::class);
        $search = $request->get('search', '');
        $permissions = Permission::with('roles')->get();
        return response(['permissions' => $permissions], 200);
    }

    public function create(Request $request)
    {
        $this->authorize('create-permissions', Permission::class);
        return response([], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create-permissions', Permission::class);
        $validated = $request->validated();
        $permission = Permission::create($validated);

        return response(['permission' => $permission], 202);
    }

    public function show(Request $request, Permission $permission)
    {
        $this->authorize('view-permissions', $permission);
        return response([], 204);
    }

    public function edit(Request $request, Permission $permission)
    {
        $this->authorize('update-permissions', $permission);
        return response(['permission' => $permission], 200);
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update-permissions', $permission);
        $validated = $request->validate([
            'roles' => 'array',
        ]);

        $roles = [];
        foreach ($validated['roles'] as $role)
            array_push($roles, Role::findByName($role, 'web'));
        $permission->syncRoles($roles);
        return response([$roles], 202);
    }

    public function destroy(Request $request, Permission $permission)
    {
        $this->authorize('delete-permissions', $permission);
        $permission->delete();
        return response([], 204);
    }

}
