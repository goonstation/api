<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventDeath;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->withSum([
                    'votes as votes' => function ($query) {
                        $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                    },
                ], 'value')
                ->with('userVotes:voteable_id,value')
                ->whereRelation('gameRound', 'ended_at', '!=', null)
                ->whereRelation('gameRound.server', 'invisible', false),
            perPage: 20
        );

        $this->setMeta(title: 'Deaths');

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
            ->withSum([
                'votes as votes' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                },
            ], 'value')
            ->with('userVotes:voteable_id,value')
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->whereRelation('gameRound.server', 'invisible', false)
            ->firstOrFail();

        $this->setMeta(
            title: 'Death #'.number_format($death->id),
            image: ['type' => 'death', 'key' => $death->id]
        );

        return Inertia::render('Events/Deaths/Show', [
            'death' => $death,
        ]);
    }
}
