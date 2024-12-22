<?php

namespace App\Jobs;

use App\Libraries\GameBuilder\Build;
use App\Models\GameAdmin;
use App\Models\GameServer;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GameBuild implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $timeout = 600;

    public $tries = 1;

    public $uniqueFor = 600;

    public GameAdmin $admin;

    public GameServer $server;

    public bool $mapSwitch = false;

    public string $queuedCacheKey = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GameAdmin $admin, GameServer $server, ?bool $mapSwitch = false)
    {
        $this->admin = $admin;
        $this->server = $server;
        $this->mapSwitch = $mapSwitch;
        $this->queuedCacheKey = "GameBuild-{$this->server->server_id}-queued";

        $isBuilding = $this->isBuilding($this->server->server_id);
        if ($isBuilding && $this->mapSwitch) {
            Cache::set($this->queuedCacheKey, [
                'type' => 'switch',
                'admin' => $this->admin->id,
                'jobLockOwner' => $isBuilding,
            ], 300);
        }
    }

    public static function isBuilding(string $serverId)
    {
        /** @var \Illuminate\Redis\Connections\PhpRedisConnection $conn */
        // @phpstan-ignore-next-line staticMethod.notFound
        $conn = Cache::lockConnection();
        $prefix = Cache::getPrefix();
        $key = "{$prefix}laravel_unique_job:".self::class.":$serverId";

        return $conn->get($key);
    }

    public function uniqueId(): string
    {
        return $this->server->server_id;
    }

    public function runQueuedBuild()
    {
        $queuedBuild = Cache::get($this->queuedCacheKey);
        if ($queuedBuild) {
            Cache::forget($this->queuedCacheKey);
            $lock = Cache::lock(
                'laravel_unique_job:'.self::class.':'.$this->uniqueId(),
                owner: $queuedBuild['jobLockOwner']
            );
            $lock->release();
            GameBuild::dispatch($this->admin, $this->server, true);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $build = new Build($this->server, $this->admin, $this->mapSwitch);
        $build->start();
        $this->runQueuedBuild();
    }

    public function failed(?\Throwable $exception): void
    {
        $this->runQueuedBuild();
    }
}
