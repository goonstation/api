<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayersController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $players = $this->indexQuery(Player::withCount(['connections', 'participations']), perPage: 30);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Players/Index', [
                'players' => $players,
            ]);
        } else {
            return $players;
        }
    }

    public function show(Request $request, Player $player)
    {
        return Inertia::render('Admin/Players/Show', [
            'player' => $player,
        ]);
    }
}
