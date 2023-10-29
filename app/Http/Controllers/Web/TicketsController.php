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
            EventTicket::whereRelation('gameRound', 'ended_at', '!=', null)
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

    public function show(Request $request, EventTicket $ticket)
    {
        return Inertia::render('Events/Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
