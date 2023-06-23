<?php

namespace App\Console\Commands;

use App\Models\GameRound;
use App\Models\PlayerParticipation;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateTest extends Command
{
    use HasGoonTeamHandling;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ghmigrate:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Running test'.PHP_EOL);

        $participations = PlayerParticipation::select('id', 'legacy_data->server_id as server_id', 'created_at')
            ->where('round_id', null)
            ->orderBy('id', 'desc')
            // ->limit(1)
            ->get();

        $rounds = GameRound::orderBy('ended_at', 'desc')->get();

        // $limit = 1;
        // $count = 0;
        $bar = $this->output->createProgressBar(count($participations));
        $bar->start();
        foreach ($participations as $participation) {
            // $count++;
            // $round = DB::selectOne(
            //     DB::raw('SELECT id FROM game_rounds gr WHERE ended_at >= :ended_at AND server_id = :server_id ORDER BY ended_at ASC LIMIT 1'),
            //     [
            //         'ended_at' => $participation->created_at,
            //         'server_id' => $participation->server_id
            //     ]
            // );

            // if (!$round) {
            //     // likely means that the participation is for a round that hasn't ended yet
            //     // which fyi probably wont be a factor for our migrations but hey whatever better to be safe
            //     $round = DB::selectOne(
            //         DB::raw('SELECT id FROM game_rounds gr WHERE created_at <= :created_at AND ended_at IS NULL AND server_id = :server_id ORDER BY ended_at ASC LIMIT 1'),
            //         [
            //             'created_at' => $participation->created_at,
            //             'server_id' => $participation->server_id
            //         ]
            //     );
            // }

            $createdAt = new DateTime($participation->created_at);
            $round = $rounds
                ->where('server_id', '=', $participation->server_id)
                ->whereNotNull('ended_at')
                ->filter(function ($value, $key) use ($createdAt) {
                    return new DateTime($value->ended_at) >= $createdAt;
                })
                ->last();

            // $this->info('Round is');
            // $this->info(json_encode($round));
            // $this->info('For participation');
            // $this->info(json_encode($participation));
            // $this->info('found round');
            // $this->info($round->id);

            $participation->round_id = $round->id;
            $participation->save();

            $bar->advance();
            // if ($count >= $limit) break;
        }
        $bar->finish();

        $this->line(PHP_EOL);

        return Command::SUCCESS;
    }
}
