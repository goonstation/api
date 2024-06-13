<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventFine;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FinesController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $fines = $this->indexQuery(
            EventFine::select('id', 'round_id', 'amount', 'issuer', 'issuer_job', 'target', 'reason')
                ->withSum([
                    'votes as votes' => function ($query) {
                        $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                    }
                ], 'value')
                ->with('userVotes:voteable_id,value')
                ->whereRelation('gameRound', 'ended_at', '!=', null)
                ->whereRelation('gameRound.server', 'invisible', false),
            perPage: 20
        );

        $this->setMeta(title: 'Fines');

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Fines/Index', [
                'fines' => $fines,
            ]);
        }

        return $fines;
    }

    public function show(Request $request, int $fine)
    {
        $fine = EventFine::select(
            'id',
            'round_id',
            'amount',
            'issuer',
            'issuer_job',
            'target',
            'reason',
            'created_at'
        )
            ->where('id', $fine)
            ->withSum([
                'votes as votes' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                }
            ], 'value')
            ->with('userVotes:voteable_id,value')
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->whereRelation('gameRound.server', 'invisible', false)
            ->firstOrFail();

        $this->setMeta(
            title: 'Fine #' . number_format($fine->id),
            image: ['type' => 'fine', 'key' => $fine->id]
        );

        return Inertia::render('Events/Fines/Show', [
            'fine' => $fine,
        ]);
    }
}
