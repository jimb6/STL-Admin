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
        $this->authorize('view-any', User::class);

        $search = $request->get('search', '');

        $users = User::search($search)
            ->latest()
            ->paginate();

        return view('app.users.index', compact('users', 'search'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        $bases = Base::pluck('base_name', 'id');

        $roles = Role::get();

        return view('app.users.create', compact('bases', 'roles'));
    }

    /**
     * @param \App\Http\Requests\UserStoreRequest $request
     * @return Response
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->syncRoles($request->roles);

        return redirect()->route('users.edit', $user);
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);

        return view('app.users.show', compact('user'));
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $bases = Base::pluck('base_name', 'id');

        $roles = Role::get();

        return view('app.users.edit', compact('user', 'bases', 'roles'));
    }

    /**
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        $user->syncRoles($request->roles);

        return redirect()->route('users.edit', $user);
    }

    /**
     * @param Request $request
     * @param \App\Models\User $user
     * @return Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index');
    }
}
