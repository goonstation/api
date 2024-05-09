<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events\EventError;
use App\Models\GameRound;
use App\Traits\IndexableQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ErrorsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $rounds = $this->indexQuery(
            GameRound::with([
                'server:server_id,name,short_name',
            ])
                ->withCount('errors')
                ->has('errors'),
            perPage: 30
        );

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Errors/Index', [
                'rounds' => $rounds,
            ]);
        } else {
            return $rounds;
        }
    }

    public function show(Request $request, GameRound $gameRound)
    {
        $gameRound->load([
            'server:server_id,name',
            'latestStationName:id,round_id,name',
            'mapRecord:id,map_id,name',
        ]);

        return Inertia::render('Admin/Errors/Show', [
            'round' => $gameRound,
        ]);
    }

    public function getErrors(GameRound $gameRound)
    {
        return EventError::where('round_id', $gameRound->id)->orderBy('created_at', 'asc')->get();
    }

    public function summary(Request $request)
    {
        $filters = $request->input('filters', []);

        $timeRange = isset($filters['time_range']) ? $filters['time_range'] : null;
        switch ($timeRange) {
            case '1week':
                $dateStart = Carbon::now()->subWeek();
                break;
            case '3days':
                $dateStart = Carbon::now()->subDays(3);
                break;
            case '1day':
                $dateStart = Carbon::now()->subDay();
                break;
            default:
                $dateStart = Carbon::now()->subWeek();
                break;
        }
        $dateStart = $dateStart->startOfDay();

        $errors = $this->indexQuery(
            EventError::select([
                DB::raw('sum(count) as overview_count'),
                DB::raw('sum(round_count) as overview_round_count'),
                DB::raw("jsonb_object_agg(round_id, jsonb_build_object('count', count, 'server_id', server_id)) as round_error_counts"),
                'name',
                'file',
                'line'
            ])
            ->fromSub(function ($query) use ($dateStart, $filters) {
                $query->select([
                    DB::raw('count(*) as count'),
                    DB::raw('count(distinct round_id) as round_count'),
                    'round_id',
                    'server_id',
                    'name',
                    'file',
                    'line',
                ])->groupBy([
                    'server_id',
                    'round_id',
                    'name',
                    'file',
                    'line',
                ])->from('events_errors', 'er')
                ->join('game_rounds', 'round_id', '=', 'game_rounds.id')
                ->where('er.created_at', '>=', $dateStart);

                if (isset($filters['server']) && $filters['server'] !== 'all') {
                    $query->where('game_rounds.server_id', $filters['server']);
                }

                if (isset($filters['overview_round_id'])) {
                    $query->where('round_id', $filters['overview_round_id']);
                }
            }, 't')
            ->groupBy([
                'name',
                'file',
                'line',
            ]),
            sortBy: 'overview_count',
            paginate: false
        );

        $errors = $errors->simplePaginateFilter(987654321);

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Errors/Summary', [
                'errors' => $errors,
            ]);
        } else {
            return $errors;
        }
    }
}
