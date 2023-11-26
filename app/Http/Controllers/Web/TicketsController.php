<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventTicket;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $tickets = $this->indexQuery(
            EventTicket::select('id', 'round_id', 'issuer', 'issuer_job', 'reason', 'target')
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
        ->whereRelation('gameRound', 'ended_at', '!=', null)
        ->whereRelation('gameRound.server', 'invisible', false)
        ->firstOrFail();

        return Inertia::render('Events/Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
