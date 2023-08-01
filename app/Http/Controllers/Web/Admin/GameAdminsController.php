<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameAdmin;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameAdminsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $gameAdmins = $this->indexQuery(GameAdmin::with(['rank']), perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/GameAdmins/Index', [
                'gameAdmins' => $gameAdmins,
            ]);
        } else {
            return $gameAdmins;
        }
    }

    public function show(Request $request, GameAdmin $gameAdmin)
    {
        return Inertia::render('Admin/GameAdmins/Show', [
            'gameAdmin' => $gameAdmin,
        ]);
    }
}
