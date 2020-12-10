<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {


    public function index(Request $request)
    {
        $this->authorize('list roles', Role::class);

        $search = $request->get('search', '');
        $roles = Role::where('name', 'like', "%{$search}%")->paginate(10);

        return response()->json([$roles], 200);
    }

    public function create()
    {
        $this->authorize('create roles', Role::class);
        $permissions = Permission::all();
//        return view('app.roles.create')->with('permissions', $permissions);
        return response()->json([$permissions], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create roles', Role::class);
        $data = $this->validate($request, [
            'name'=>'required|unique:roles|max:32',
            'permissions' =>'array',
        ]);
        $role = Role::create($data);
        $permissions = Permission::find($request->permissions);
        $role->syncPermissions($permissions);
        return response()->json([$role, $permissions], 201);
//        return redirect()->route('roles.edit', $role->id);
    }

    public function show(Role $role)
    {
        $this->authorize('view roles', Role::class);

        return view('settings.roles.index')->with('role', $role);
    }

    public function edit(Role $role)
    {
        $this->authorize('update roles', $role);

        $permissions = Permission::all();

        return view('app.roles.edit')
            ->with('role', $role)
            ->with('permissions', $permissions);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update roles', $role);

        $data = $this->validate($request, [
            'name'=>'required|max:32|unique:roles,name,'.$role->id,
            'permissions' =>'array',
        ]);

        $role->update($data);

        $permissions = Permission::find($request->permissions);
        $role->syncPermissions($permissions);

        return response()->json([$role], 202);
//        return redirect()->route('roles.edit', $role->id);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json([], 204);
//        return redirect()->route('roles.index');
    }

}
