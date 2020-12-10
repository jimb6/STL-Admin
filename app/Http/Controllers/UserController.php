<?php

namespace App\Http\Controllers;

use App\Models\Base;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('list users', User::class);
        $search = $request->get('search', '');
        $users = User::search($search)->with(['base', 'address'])->get();
        return \response([$users], 200)->header('Content-type', 'application/json');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $this->authorize('create users', User::class);
        $bases = Base::pluck('base_name', 'id');
        $roles = Role::get();
        return \response([$bases, $roles], 200);
    }

    /**
     * @param \App\Http\Requests\UserStoreRequest $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->authorize('create users', User::class);
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $user->syncRoles($request->roles);
        return \response(['message'=>'User Created Successfully!'],201);
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view users', $user);
        return \response([$user], 200);
//        return view('app.users.show', compact('user'));
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update users', $user);
        $bases = Base::pluck('base_name', 'id');
        $roles = Role::get();
        return \response([$bases, $roles], 200);
//        return view('app.users.edit', compact('user', 'bases', 'roles'));
    }

    /**
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update users', $user);
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        $user->update($validated);
        $user->syncRoles($request->roles);
        return \response(['message'=>'User Updated Successfully!'], 202);
//        return redirect()->route('users.edit', $user);
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete users', $user);
        $user->delete();
        return \response([], 204);
//        return redirect()->route('users.index');
    }
}
