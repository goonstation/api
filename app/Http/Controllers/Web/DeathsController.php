<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventDeath;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeathsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $deaths = $this->indexQuery(
            EventDeath::select(
                'id',
                'round_id',
                'bruteloss',
                'fireloss',
                'gibbed',
                'last_words',
                'mob_job',
                'mob_name',
                'oxyloss',
                'toxloss'
            )
                ->whereRelation('gameRound', 'ended_at', '!=', null)
                ->whereRelation('gameRound.server', 'invisible', false),
            perPage: 20
        );

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Deaths/Index', [
                'deaths' => $deaths,
            ]);
        }

        return $deaths;
    }

    public function show(Request $request, int $death)
    {
        $death = EventDeath::select(
            'id',
            'round_id',
            'bruteloss',
            'fireloss',
            'gibbed',
            'last_words',
            'mob_job',
            'mob_name',
            'oxyloss',
            'toxloss'
        )
            ->where('id', $death)
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->whereRelation('gameRound.server', 'invisible', false)
            ->firstOrFail();

        return Inertia::render('Events/Deaths/Show', [
            'death' => $death,
        ]);
    }
}
