<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameBuildCancelRequest;
use App\Http\Requests\GameBuildCreateRequest;
use App\Models\GameAdmin;
use App\Models\GameBuild;
use App\Traits\IndexableQuery;
use App\Traits\ManagesGameBuilds;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GameBuildsController extends Controller
{
    use IndexableQuery, ManagesGameBuilds;

    private function getCounts()
    {
        return GameBuild::select([
            DB::raw('count(id) as total'),
            DB::raw('sum(case when failed = false and cancelled = false then 1 else 0 end) as success'),
            DB::raw('sum(case when failed = true then 1 else 0 end) as failed'),
            DB::raw('sum(case when cancelled = true then 1 else 0 end) as cancelled'),
            DB::raw('extract(epoch from avg(ended_at - created_at))::int as avg_duration'),
        ])
            ->whereNotNull('ended_at')
            ->first()
            ->makeHidden('duration');
    }

    private function getChartData()
    {
        return GameBuild::select([
            DB::raw("date_trunc('day', created_at) as day"),
            DB::raw('sum(case when failed = false and cancelled = false then 1 else 0 end) as success'),
            DB::raw('sum(case when failed = true then 1 else 0 end) as failed'),
            DB::raw('sum(case when cancelled = true then 1 else 0 end) as cancelled'),
        ])
            ->whereRaw("created_at >= CURRENT_DATE - interval '30 days'")
            ->whereRaw('created_at < CURRENT_DATE')
            ->whereNotNull('ended_at')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->makeHidden('duration');
    }

    private function getBuilds()
    {
        return $this->indexQuery(
            GameBuild::with([
                'gameServer:id,server_id,short_name',
                'startedBy',
            ])->whereNotNull('ended_at')
        );
    }

    public function index()
    {
        return Inertia::render('Admin/GameBuilds/Index', [
            'builds' => Inertia::lazy(fn () => $this->getBuilds()),
            'counts' => Inertia::defer(fn () => $this->getCounts(), 'counts'),
            'chart' => Inertia::defer(fn () => $this->getChartData(), 'chart'),
        ]);
    }

    public function status()
    {
        return $this->getStatus();
    }

    public function show(GameBuild $build)
    {
        $build->load([
            'gameServer:id,server_id,short_name,name',
            'startedBy:id,name,ckey',
            'cancelledBy:id,name,ckey',
            'map:map_id,name',
            'logs' => function (Builder $q) {
                $q->select(['id', 'build_id', 'type', 'group', 'log', 'created_at'])
                    ->orderBy(DB::raw('id, created_at'));
            },
        ]);

        return Inertia::render('Admin/GameBuilds/Show', [
            'build' => $build,
            'testMergeAuthors' => Inertia::lazy(function () use ($build) {
                $ret = [];
                foreach ($build->test_merges as $testMerge) {
                    $ret[] = [
                        'pr_id' => $testMerge['pr_id'],
                        'added_by' => GameAdmin::firstWhere('id', $testMerge['added_by']),
                        'updated_by' => GameAdmin::firstWhere('id', $testMerge['updated_by']),
                    ];
                }

                return $ret;
            }),
        ]);
    }

    public function store(GameBuildCreateRequest $request)
    {
        $request = $request->merge([
            'game_admin_ckey' => $request->user()->gameAdmin->ckey,
        ]);

        try {
            $this->addBuild($request);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back();
    }

    public function cancel(GameBuildCancelRequest $request)
    {
        $request = $request->merge([
            'game_admin_ckey' => $request->user()->gameAdmin->ckey,
        ]);
        $this->cancelBuild($request);

        return ['message' => 'Success'];
    }
}
