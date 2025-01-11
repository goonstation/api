<?php

namespace App\Jobs;

use App\Jobs\GameBuild as GameBuildJob;
use App\Models\GameAdmin;
use App\Models\GameBuild;
use App\Models\GameBuildSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class GameBuildOnRepoUpdate implements ShouldQueue
{
    use Queueable;

    protected $timeout = 30;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $settings = GameBuildSetting::with(['gameServer'])->whereRelation('gameServer', 'active', true)->get();
        $branches = $settings->unique('branch')->pluck('branch');

        $latestRemoteCommits = [];
        foreach ($branches as $branch) {
            $res = null;
            try {
                $res = Http::withHeaders([
                    'Accept: application/vnd.github.sha',
                    'Authorization: Bearer '.config('github.user_token'),
                    'X-Github-Api-Version: 2022-11-28',
                    'User-Agent: Goonhub',
                ])
                    ->get("https://api.github.com/repos/goonstation/goonstation/commits/$branch");
            } catch (ConnectionException) {
                continue;
            }

            if (is_null($res)) {
                continue;
            }

            $item = $res->json();
            $latestRemoteCommits[$branch] = $item['sha'];
        }

        $botAdmin = GameAdmin::whereRelation('rank', 'rank', 'Bot')->first();
        foreach ($settings as $setting) {
            if (! array_key_exists($setting->branch, $latestRemoteCommits)) {
                continue;
            }

            $latestBuild = GameBuild::where('server_id', $setting->gameServer->server_id)->latest()->first();

            if (! $latestBuild || $latestBuild->commit !== $latestRemoteCommits[$setting->branch]) {
                GameBuildJob::dispatch($botAdmin, $setting->gameServer);
            }
        }
    }
}
