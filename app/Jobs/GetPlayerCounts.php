<?php

namespace App\Jobs;

use App\Libraries\GameBridge;
use App\Models\PlayersOnline;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetPlayerCounts implements ShouldQueue
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
        // loop through active servers
        // TODO: (determined how? from that dumb servers.conf file? manually?)
        $servers = ['main1', 'main3', 'main4', 'main5'];

        $when = Carbon::now();
        foreach ($servers as $server) {
            $playerCount = 0;
            try {
                $status = GameBridge::status($server);
                $playerCount = $status['players'];
            } catch (\Exception $e) {
                // server might be dead, restarting, or otherwise in a bad state
                // we want uniformly inserted data for online players soooo default to 0
                // we'll just have to remove outliers like this when showing averages later
            }
            $playersOnline = new PlayersOnline();
            $playersOnline->timestamps = false;
            $playersOnline->server_id = $server;
            $playersOnline->online = $playerCount;
            $playersOnline->created_at = $when;
            $playersOnline->save();
        }
    }
}
