<?php

namespace App\Jobs;

use App\Libraries\GameBuilder\Build;
use App\Models\GameAdmin;
use App\Models\GameServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Test implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $server = GameServer::where('server_id', 'main1')->first();
        $admin = GameAdmin::where('ckey', 'wirewraith')->first();
        $build = new Build($server, $admin);
        $build->build();
    }
}
