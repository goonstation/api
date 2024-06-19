<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $users = $this->indexQuery(User::with('teams'), perPage: 30);
        foreach ($users as $user) {
            $user->all_teams = $user->allTeams();
        }

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Users/Index', [
                'users' => $users,
            ]);
        } else {
            return $users;
        }
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'discord_id' => 'nullable',
            'game_admin_id' => 'nullable',
            'is_admin' => 'boolean',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->discord_id = isset($data['discord_id']) ? $data['discord_id'] : null;
        $user->game_admin_id = isset($data['game_admin_id']) ? $data['game_admin_id'] : null;
        $user->password = Hash::make($data['password']);

        // Only current admins can modify other admin status
        if ($request->user()->is_admin) {
            $user->is_admin = $data['is_admin'];
        }

        $user->save();

        return to_route('admin.users.index');
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'editUser' => $user->only([
                'id',
                'name',
                'email',
                'profile_photo_url',
                'is_admin',
                'discord_id',
                'game_admin_id',
            ]),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'confirm_password' => 'required_with:password|same:password',
            'discord_id' => 'nullable',
            'game_admin_id' => 'nullable',
            'is_admin' => 'boolean',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->discord_id = isset($data['discord_id']) ? $data['discord_id'] : null;
        $user->game_admin_id = isset($data['game_admin_id']) ? $data['game_admin_id'] : null;

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Only current admins can modify other admin status
        $user = $request->user();
        if ($user->is_admin && $user->id !== $user->id) {
            $user->is_admin = $data['is_admin'];
        }

        $user->save();

        return redirect()->back();
    }
}
