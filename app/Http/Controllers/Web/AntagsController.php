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

    public function show(Request $request, EventAntag $antag)
    {
        $antag->load(['objectives', 'itemPurchases']);

        return Inertia::render('Events/Antags/Show', [
            'antag' => $antag,
        ]);
    }
}
