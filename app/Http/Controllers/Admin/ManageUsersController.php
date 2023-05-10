<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateUserRequest;

class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        if ($request->user()->cannot('viewAny', User::class)) {
            abort(404);
        }
        // $users = User::whereIn('role', ['superadmin', 'admin'])->latest()->get()->toArray();
        return Inertia::render('Admin/Users');
    }



    public function getPaginated(Request $request)
    {
        if ($request->user()->cannot('viewAny', User::class)) {
            abort(404);
        }
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'filter' => 'nullable|array|max:1', // Only supporting 1 filter
            'filter.*.field' => 'required|string|max:255',
            'filter.*.type' => 'required|string|max:255',
            'filter.*.value' => 'required|string|max:255',
        ]);
        $filter = $request->input('filter.0');
        $size = $request->input('size', 15);

        $users = User::query();

        if ($filter && $filter['type'] === 'like' && $filter['field'] === 'name') {
            $users = $users->where('name', 'LIKE', '%' . $filter['value'] . '%');
        }

        return $users->whereIn('role', ['superadmin', 'admin'])->latest()->paginate($size, ['*']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->cannot('create', User::class)) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'username' => 'required|string|max:15|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(User::$roles)],
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return to_route('users.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->fill($validated);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        return to_route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->deleteOrFail();
        return to_route('users.index');
    }
}
