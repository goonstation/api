<?php

namespace App\Jobs;

use App\Models\GlobalStat;
use App\Models\PlayerConnection;
use App\Models\PlayerParticipation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class GenerateGlobalPlayerStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

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
        // Unique player participations per day
        $data = PlayerParticipation::select(
            DB::raw('Date(created_at) as date'),
            DB::raw('count(distinct player_id) as connections')
        )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $niceData = [];
        foreach ($data as $conn) {
            $niceData[] = [$conn->date, $conn->connections];
        }
        $playerParticipationsPerDay = $niceData;
        GlobalStat::updateOrCreate(
            ['key' => 'unique_player_participations_per_day'],
            ['stats' => json_encode($playerParticipationsPerDay)]
        );

        // Countries by unique player connections
        $data = PlayerConnection::select(
            'country',
            'country_iso',
            DB::raw('count(distinct player_id) as unique_connections')
        )
            ->where('country', '!=', null)
            ->groupBy('country', 'country_iso')
            ->orderBy('unique_connections', 'desc')
            ->get();

        $total = $data->sum('unique_connections');

        $niceData = [];
        $groupedCountries = 0;
        foreach ($data as $conn) {
            $percent = (float) number_format($conn->unique_connections / $total * 100, 2, '.', '');
            if ($percent <= 2) {
                $groupedCountries += $percent;
            } else {
                $niceData[] = [$conn->country, (float) $percent];
            }
        }

        $niceData[] = ['Other', $groupedCountries];
        $playersByCountry = $niceData;
        GlobalStat::updateOrCreate(
            ['key' => 'players_by_country'],
            ['stats' => json_encode($playersByCountry)]
        );
    }
}
