<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {


    public function index(Request $request)
    {
        $this->authorize('list', Role::class);

        $search = $request->get('search', '');
        $roles = Role::where('name', 'like', "%{$search}%")->paginate(10);

        return view('app.roles.index')
            ->with('roles', $roles)
            ->with('search', $search);
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::all();

        return view('app.roles.create')->with('permissions', $permissions);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $this->validate($request, [
            'name'=>'required|unique:roles|max:32',
            'permissions' =>'array',
        ]);

        $role = Role::create($data);

        $permissions = Permission::find($request->permissions);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.edit', $role->id);
    }

    public function show(Role $role)
    {
        $this->authorize('view', Role::class);

        return view('app.roles.show')->with('role', $role);
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        $permissions = Permission::all();

        return view('app.roles.edit')
            ->with('role', $role)
            ->with('permissions', $permissions);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $this->validate($request, [
            'name'=>'required|max:32|unique:roles,name,'.$role->id,
            'permissions' =>'array',
        ]);

        $role->update($data);

        $permissions = Permission::find($request->permissions);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.edit', $role->id);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return redirect()->route('roles.index');
    }

}
