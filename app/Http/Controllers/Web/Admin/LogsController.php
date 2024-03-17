<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameRound;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $rounds = $this->indexQuery(
            GameRound::with([
                'server:server_id,name,short_name',
            ])->has('logs'),
            perPage: 30
        );

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Logs/Index', [
                'rounds' => $rounds,
            ]);
        } else {
            return $rounds;
        }
    }

    public function show(Request $request, GameRound $gameRound)
    {
        $gameRound->load([
            'logs' => function($q) {
                $q->orderBy('created_at', 'asc');
            }
        ]);

        return Inertia::render('Admin/Logs/Show', [
            'round' => $gameRound,
        ]);
    }
}
