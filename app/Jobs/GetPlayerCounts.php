<?php

namespace App\Jobs;

use App\Libraries\GameBridge;
use App\Models\GameServer;
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
        // Get player count for all active servers
        $servers = GameServer::where('active', true)->where('invisible', false)->get();

        $when = Carbon::now();
        foreach ($servers as $server) {
            $playerCount = 0;
            try {
                $status = GameBridge::status($server->server_id);
                $playerCount = $status['players'];
            } catch (\Exception $e) {
                $playerCount = null;
            }
            $playersOnline = new PlayersOnline();
            $playersOnline->timestamps = false;
            $playersOnline->server_id = $server->server_id;
            $playersOnline->online = $playerCount;
            $playersOnline->created_at = $when;
            $playersOnline->save();
        }
    }
}
