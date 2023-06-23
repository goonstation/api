<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $deaths = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_deaths\';'));
        $tickets = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_tickets\';'));
        $fines = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_fines\';'));
        $bees = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_bee_spawns\';'));

        // Total of all event types:
        // SELECT sum(reltuples) as estimate FROM pg_class where relname like 'events_%' and relkind = 'r';

        /* Get event count in last n days, grouped by day
        SELECT count(id) as events, DATE_TRUNC('day', created_at) as "day"
        FROM events_deaths
        WHERE created_at >= DATE_TRUNC('day', NOW()) - INTERVAL '7 days'
        GROUP BY DATE_TRUNC('day', created_at)
        ORDER BY "day" DESC;
        */

        return Inertia::render('Events/Index', [
            'counts' => [
                'deaths' => $deaths->estimate,
                'tickets' => $tickets->estimate,
                'fines' => $fines->estimate,
                'bees' => $bees->estimate,
            ],
        ]);
    }
}
