<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventTicket;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TicketsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $tickets = $this->indexQuery(
            EventTicket::select('id', 'round_id', 'issuer', 'issuer_job', 'reason', 'target')
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

        if ($this->wantsInertia()) {
            return Inertia::render('Events/Tickets/Index', [
                'tickets' => $tickets,
            ]);
        }

        return $tickets;
    }

    public function show(Request $request, int $ticket)
    {
        $ticket = EventTicket::select(
            'id',
            'round_id',
            'issuer',
            'issuer_job',
            'reason',
            'target',
            'created_at'
        )
            ->where('id', $ticket)
            ->withSum([
                'votes as votes' => function ($query) {
                    $query->select(DB::raw('COALESCE(SUM(value), 0)'));
                }
            ], 'value')
            ->with('userVotes:voteable_id,value')
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->whereRelation('gameRound.server', 'invisible', false)
            ->firstOrFail();

        return Inertia::render('Events/Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
