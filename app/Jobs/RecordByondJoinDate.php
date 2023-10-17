<?php

namespace App\Jobs;

use App\Models\Player;
use App\Models\PlayerConnection;
use GeoIp2\Database\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class RecordByondJoinDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $playerId;
    private $ckey;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $playerId, string $ckey)
    {
        $this->playerId = $playerId;
        $this->ckey = $ckey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $byondJoinDate = $this->getByondJoinDate($this->ckey);
        Player::where('id', $this->playerId)
            ->update([
                'byond_join_date' => $byondJoinDate
            ]);
    }

    /**
     * Query byond for the join date of an account
     *
     * @param  string  $ckey
     * @return string|null
     */
    private function getByondJoinDate(string $ckey)
    {
        $response = Http::get("https://secure.byond.com/members/$ckey?format=text");
        if ($response->failed()) {
            return null;
        }
        preg_match('/joined = "(.*)"/i', $response->body(), $matches);
        if (empty($matches[1])) {
            return null;
        }

        return $matches[1];
    }
}
