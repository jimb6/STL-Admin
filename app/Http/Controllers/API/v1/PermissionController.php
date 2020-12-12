<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{



    public function index(Request $request)
    {
        $this->authorize('list permissions', Permission::class);

        $search = $request->get('search', '');
        $permissions = Permission::where('name', 'like', "%{$search}%")->get();

        return response([$permissions, $search], 200);

//        return view('app.permissions.index')
//            ->with('permissions', $permissions)
//            ->with('search', $search);
    }


    public function create()
    {
        $this->authorize('create permissions', Permission::class);

        $roles = Role::all();
//        return view('app.permissions.create')->with('roles', $roles);
        return response()->json([$roles], 202);
    }


    public function store(Request $request)
    {
        $this->authorize('create permissions', Permission::class);

        $data = $this->validate($request, [
            'name' => 'required|max:64',
            'roles' => 'array'
        ]);

        $permission = Permission::create($data);

        $roles = Role::find($request->roles);
        $permission->syncRoles($roles);

        return response()->json([$permission], 201);
//        return redirect()->route('permissions.edit', $permission->id);
    }


    public function show(Permission $permission)
    {
        $this->authorize('view', Permission::class);

        return response()->json([$permission], 200);
//        return view('app.permissions.show')->with('permission', $permission);
    }


    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);

        $roles = Role::get();

        return response()->json([$permission, $roles], 200);
//        return view('app.permissions.edit')
//            ->with('permission', $permission)
//            ->with('roles', $roles);
    }


    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $data = $this->validate($request, [
            'name' => 'required|max:40',
            'roles' => 'array'
        ]);

        $permission->update($data);

        $roles = Role::find($request->roles);
        $permission->syncRoles($roles);

        return response()->json($permission, 202);
//        return redirect()->route('permissions.edit', $permission->id);
    }


    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);

        $permission->delete();

//        return redirect()->route('permissions.index');
        return response()->json([], 202);
    }
}
