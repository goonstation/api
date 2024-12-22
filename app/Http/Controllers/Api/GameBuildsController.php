<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameBuildCancelRequest;
use App\Http\Requests\GameBuildCreateRequest;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\GameBuildResource;
use App\Http\Resources\GameBuildStatusCurrentResource;
use App\Http\Resources\GameBuildStatusQueuedResource;
use App\Http\Resources\GameBuildStatusResource;
use App\Jobs\GameBuild as GameBuildJob;
use App\Models\GameAdmin;
use App\Models\GameBuild;
use App\Models\GameServer;
use App\Rules\DateRange;
use App\Traits\IndexableQuery;
use App\Traits\ManagesGameBuilds;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

/**
 * @tags Game Builds
 */
class GameBuildsController extends Controller
{
    use IndexableQuery, ManagesGameBuilds;

    /**
     * List
     *
     * List filtered and paginated bans
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<GameBuildResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        $request->validate([
            'filters.id' => 'int',
            /** @example main1 */
            'filters.server' => 'string',
            'filters.started_by' => 'string',
            'filters.branch' => 'string',
            'filters.commit' => 'string',
            'filters.map_id' => 'string',
            'filters.failed' => 'boolean',
            'filters.cancelled' => 'boolean',
            'filters.map_switch' => 'boolean',
            'filters.cancelled_by' => 'string',
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.created_at' => new DateRange,
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.updated_at' => new DateRange,
            /**
             * A date or date range
             *
             * @example 2023/01/30 12:00:00 - 2023/02/01 12:00:00
             */
            'filters.ended_at' => new DateRange,
        ]);

        return GameBuildResource::collection(
            $this->indexQuery(GameBuild::class)
        );
    }

    /**
     * Status
     *
     * Get the current status of game builds in process or queued
     */
    public function status()
    {
        $res = ['current' => [], 'queued' => []];
        /** @var \Illuminate\Redis\Connections\PhpRedisConnection */
        $redis = Queue::getRedis();
        $reservedJobs = $redis->zrange('queues:default:reserved', 0, -1);
        foreach ($reservedJobs as $job) {
            $job = json_decode($job);
            if ($job->data->commandName === GameBuildJob::class) {
                $runningJob = collect();
                foreach ($job->tags as $tag) {
                    if (str_starts_with($tag, GameAdmin::class)) {
                        [$type, $id] = explode(':', $tag);
                        $runningJob->put('admin', GameAdmin::firstWhere('id', $id));
                    } elseif (str_starts_with($tag, GameServer::class)) {
                        [$type, $id] = explode(':', $tag);
                        $runningJob->put('server', GameServer::firstWhere('id', $id));
                    }
                }

                if ($runningJob->has('server')) {
                    $queuedJob = Cache::get("GameBuild-{$runningJob->get('server')->server_id}-queued");
                    if ($queuedJob) {
                        $res['queued'][] = new GameBuildStatusQueuedResource([
                            'admin' => GameAdmin::firstWhere('id', $queuedJob['admin']),
                            'server' => $runningJob->get('server'),
                            'type' => $queuedJob['type'],
                        ]);
                    }
                }

                $res['current'][] = new GameBuildStatusCurrentResource($runningJob);
            }
        }

        return new GameBuildStatusResource($res);
    }

    /**
     * Build
     *
     * Run a game build
     */
    public function build(GameBuildCreateRequest $request)
    {
        $this->addBuild($request);

        return ['message' => 'Success'];
    }

    /**
     * Cancel
     *
     * Cancel a build
     */
    public function cancel(GameBuildCancelRequest $request)
    {
        $this->cancelBuild($request);

        return ['message' => 'Success'];
    }
}
