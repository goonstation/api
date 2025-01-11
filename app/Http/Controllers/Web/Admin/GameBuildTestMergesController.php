<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameBuildTestMergeCreateRequest;
use App\Models\GameBuildTestMerge;
use App\Traits\IndexableQuery;
use App\Traits\ManagesGameBuildTestMerges;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class GameBuildTestMergesController extends Controller
{
    use IndexableQuery, ManagesGameBuildTestMerges;

    private function getTestMergeGroups()
    {
        return GameBuildTestMerge::with([
            'buildSettings',
            'buildSettings.gameServer',
            'addedBy',
            'updatedBy',
        ])
            ->get()
            ->groupBy('pr_id')
            ->map(function ($item) {
                return $item->sortBy('buildSettings.gameServer.id')->values()->all();
            });
    }

    private function getPullRequestDetails(int $prId)
    {
        $res = null;
        try {
            $res = Http::withHeaders([
                'Accept: application/vnd.github+json',
                'Authorization: Bearer '.config('github.user_token'),
                'X-Github-Api-Version: 2022-11-28',
                'User-Agent: Goonhub',
            ])
                ->asJson()
                ->get("https://api.github.com/repos/goonstation/goonstation/pulls/$prId");
        } catch (ConnectionException) {
            return null;
        }

        if (is_null($res)) {
            return null;
        }

        $item = $res->json();

        return [
            'number' => $item['number'],
            'merged' => $item['merged'],
            'locked' => $item['locked'],
            'draft' => $item['draft'],
            'title' => $item['title'],
            'user' => [
                'login' => $item['user']['login'],
                'html_url' => $item['user']['html_url'],
            ],
            'head' => [
                'sha' => $item['head']['sha'],
            ],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }

    private function getPullRequestList(int $page = 1)
    {
        $items = collect();
        $res = null;
        try {
            $res = Http::withHeaders([
                'Accept: application/vnd.github+json',
                'Authorization: Bearer '.config('github.user_token'),
                'X-Github-Api-Version: 2022-11-28',
                'User-Agent: Goonhub',
            ])
                ->asJson()
                ->get('https://api.github.com/repos/goonstation/goonstation/pulls', [
                    'per_page' => 100,
                    'page' => $page,
                ]);
        } catch (ConnectionException) {
            return null;
        }

        if (is_null($res)) {
            return null;
        }

        $data = $res->json();

        foreach ($data as $item) {
            $items->add([
                'number' => $item['number'],
                'locked' => $item['locked'],
                'draft' => $item['draft'],
                'title' => $item['title'],
                'user' => [
                    'login' => $item['user']['login'],
                    'html_url' => $item['user']['html_url'],
                ],
                'head' => [
                    'sha' => $item['head']['sha'],
                ],
                'created_at' => $item['created_at'],
                'updated_at' => $item['updated_at'],
            ]);
        }

        if ($res->hasHeader('link')) {
            preg_match('/.*page=(\d+)(?=>; rel="Next")/i', $res->getHeader('link')[0], $matches);
            if ($matches && count($matches) > 1) {
                $nextPage = (int) $matches[1];
                $newItems = $this->getPullRequestList($nextPage);
                $items = $items->merge($newItems);
            }
        }

        return $items;
    }

    public function pullRequestDetails(int $prId)
    {
        $details = Cache::remember("pr-details-$prId", 300, fn () => $this->getPullRequestDetails($prId));
        if (! $details) {
            return abort(503, 'Failed to fetch pull request details from GitHub, please try again.');
        }

        return $details;
    }

    public function pullRequests()
    {
        $details = Cache::remember('pr-list', 300, fn () => $this->getPullRequestList());
        if (! $details) {
            return abort(503, 'Failed to fetch pull request details from GitHub, please try again.');
        }

        return $details;
    }

    public function index()
    {
        return Inertia::render('Admin/GameBuilds/TestMerges/Index', [
            'pullRequests' => $this->getTestMergeGroups(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/GameBuilds/TestMerges/Create', [
            'pullRequests' => $this->getTestMergeGroups(),
        ]);
    }

    public function store(GameBuildTestMergeCreateRequest $request)
    {
        $request->merge([
            'game_admin_ckey' => $request->user()->gameAdmin->ckey,
        ]);

        try {
            $this->addTestMerge($request);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return to_route('admin.builds.test-merges.index');
    }

    public function editCommit(GameBuildTestMerge $testMerge)
    {
        return Inertia::render('Admin/GameBuilds/TestMerges/UpdateCommit', [
            'pullRequests' => $this->getTestMergeGroups(),
            'testMerge' => $testMerge,
        ]);
    }

    public function updateCommit(Request $request, GameBuildTestMerge $testMerge)
    {
        $request->validate([
            'commit' => 'required|string',
        ]);

        if ($request['commit'] !== $testMerge->commit) {
            $testMerge->commit = $request['commit'];
            $testMerge->updatedBy()->associate($request->user()->gameAdmin);
            $testMerge->save();
        }

        return to_route('admin.builds.test-merges.index');
    }

    public function updateCommits(Request $request, int $prId)
    {
        $details = Cache::remember(
            "pr-details-$prId",
            300,
            fn () => $this->getPullRequestDetails($prId)
        );

        if (! $details) {
            return abort(503, 'Failed to fetch details from GitHub, please try again.');
        }

        $testMerges = GameBuildTestMerge::where('pr_id', $prId)->get();

        foreach ($testMerges as $testMerge) {
            if ($details['head']['sha'] !== $testMerge->commit) {
                $testMerge->commit = $details['head']['sha'];
                $testMerge->updatedBy()->associate($request->user()->gameAdmin);
                $testMerge->save();
            }
        }

        return ['message' => 'Test merge commits updated'];
    }

    public function destroy(GameBuildTestMerge $testMerge)
    {
        $testMerge->delete();

        return ['message' => 'Test merge removed'];
    }

    public function destroyMulti(Request $request)
    {
        $data = $this->validate($request, [
            'ids' => 'required|array',
        ]);

        $testMerges = GameBuildTestMerge::whereIn('id', $data['ids']);
        $testMerges->delete();

        return ['message' => 'Test merges removed'];
    }
}
