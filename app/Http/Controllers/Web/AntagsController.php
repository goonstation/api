<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventAntag;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AntagsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $antags = $this->indexQuery(
            EventAntag::with([
                'objectives:id,round_id,player_id,success',
            ])
                ->select(
                    'id',
                    'round_id',
                    'player_id',
                    'mob_job',
                    'mob_name',
                    'success',
                    'traitor_type'
                )
                ->whereRelation('gameRound', 'ended_at', '!=', null)
                ->whereRelation('gameRound.server', 'invisible', false),
            perPage: 20
        );

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Antags/Index', [
                'antags' => $antags,
            ]);
        }

        return $antags;
    }

    public function show(Request $request, int $antag)
    {
        $antag = EventAntag::with([
            'objectives:id,round_id,player_id,objective,success',
            'itemPurchases:id,round_id,player_id,item',
        ])
            ->select(
                'id',
                'round_id',
                'player_id',
                'mob_job',
                'mob_name',
                'success',
                'traitor_type'
            )
            ->where('id', $antag)
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->whereRelation('gameRound.server', 'invisible', false)
            ->firstOrFail();

        return Inertia::render('Events/Antags/Show', [
            'antag' => $antag,
        ]);
    }
}
