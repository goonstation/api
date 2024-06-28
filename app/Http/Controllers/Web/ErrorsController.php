<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events\EventError;
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
                DB::raw('(array_agg(names[1]))[1] as overview_name'),
                'file',
                'line',
            ])
                ->fromSub(function ($query) use ($dateStart, $filters, $request) {
                    $query->select([
                        DB::raw('count(*) as count'),
                        DB::raw('count(distinct round_id) as round_count'),
                        'round_id',
                        'server_id',
                        DB::raw('array_agg(name) as names'),
                        'file',
                        'line',
                    ])->groupBy([
                        'server_id',
                        'round_id',
                        'file',
                        'line',
                    ])->from('events_errors', 'er')
                        ->join('game_rounds', 'round_id', '=', 'game_rounds.id')
                        ->where('er.created_at', '>=', $dateStart);

                    // Temp patch for errors with null file and line columns
                    $query->whereRaw('(file is not null and line is not null)');

                    // Non-admins can't view errors within secret module files
                    if (! $request->user()?->isGameAdmin()) {
                        $query->whereRaw('starts_with(file, \'code_secret\') = false');
                    }

                    if (isset($filters['server']) && $filters['server'] !== 'all') {
                        $query->where('game_rounds.server_id', $filters['server']);
                    }

                    if (isset($filters['overview_round_id'])) {
                        $query->where('round_id', $filters['overview_round_id']);
                    }

                    if (isset($filters['overview_name'])) {
                        $query->where('name', 'ILIKE', '%'.$filters['overview_name'].'%');
                    }
                }, 't')
                ->groupBy([
                    'file',
                    'line',
                ]),
            sortBy: 'overview_count',
            paginate: false
        );

        $errors = $errors->simplePaginateFilter(987654321);

        $this->setMeta(title: 'Errors');
        if ($this->wantsInertia($request)) {
            return Inertia::render('Events/Errors/Index', [
                'errors' => $errors,
            ]);
        } else {
            return $errors;
        }
    }
}
