<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $deaths = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_deaths\';')->getValue(DB::connection()->getQueryGrammar()));
        $tickets = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_tickets\';')->getValue(DB::connection()->getQueryGrammar()));
        $fines = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_fines\';')->getValue(DB::connection()->getQueryGrammar()));
        $bees = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_bee_spawns\';')->getValue(DB::connection()->getQueryGrammar()));
        $antags = DB::selectOne(DB::raw('SELECT reltuples AS estimate FROM pg_class where relname = \'events_antags\';')->getValue(DB::connection()->getQueryGrammar()));

        // Total of all event types:
        // SELECT sum(reltuples) as estimate FROM pg_class where relname like 'events_%' and relkind = 'r';

        $this->setMeta(title: 'Events');

        return Inertia::render('Events/Index', [
            'counts' => [
                'deaths' => $deaths->estimate,
                'tickets' => $tickets->estimate,
                'fines' => $fines->estimate,
                'bees' => $bees->estimate,
                'antags' => $antags->estimate,
            ],
        ]);
    }

    public function stats(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in([
                'antags',
                'deaths',
                'tickets',
                'fines',
                'bee_spawns',
            ])],
        ]);

        $type = $data['type'];

        $stats = Cache::remember(
            'event_stats_'.$type,
            Carbon::tomorrow()->startOfDay(),
            function () use ($type) {
                return DB::table('events_'.$type)
                    ->selectRaw('count(id) as events')
                    ->selectRaw('Date(created_at) as "day"')
                    ->where('created_at', '>=', Carbon::now()->subYear())
                    ->groupByRaw('Date(created_at)')
                    ->orderBy('day', 'desc')
                    ->get();
            }
        );

        return $stats;
    }
}
