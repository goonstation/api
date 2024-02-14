<?php

namespace App\Jobs;

use App\Models\PlayerConnection;
use GeoIp2\Database\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TypeError;

class RecordPlayerConnection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $playerId;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $playerId, array $data)
    {
        $this->playerId = $playerId;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $geoReader = new Reader(storage_path('app').'/GeoLite2-Country/GeoLite2-Country.mmdb');
        $geoRecord = null;
        try {
            $geoRecord = $geoReader->country($this->data['ip']);
        } catch (\Exception|TypeError $e) {
            // pass
        }

        $connection = new PlayerConnection();
        $connection->player_id = $this->playerId;
        $connection->round_id = $this->data['round_id'];
        $connection->ip = $this->data['ip'];
        $connection->comp_id = $this->data['comp_id'];
        $connection->country = $geoRecord ? $geoRecord->country->name : null;
        $connection->country_iso = $geoRecord ? $geoRecord->country->isoCode : null;
        $connection->save();
    }
}
