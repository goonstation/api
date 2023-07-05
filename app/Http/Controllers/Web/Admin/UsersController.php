<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
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
}
