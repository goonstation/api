<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayersController extends Controller
{
    public function index(Request $request)
    {
        $players = Player::filter($request->input('filters', []))
            ->orderBy(
                $request->input('sort_by', 'id'),
                $request->input('descending', 'true') === 'true' ? 'desc' : 'asc'
            )
            ->withCount(['connections', 'participations'])
            ->paginateFilter($request->input('per_page', 15));

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Players/Index', [
                'players' => $players,
            ]);
        } else {
            return $players;
        }
    }
}
